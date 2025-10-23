from fastapi import FastAPI, APIRouter, HTTPException, Depends, Request, Header
from fastapi.security import HTTPBearer, HTTPAuthorizationCredentials
from dotenv import load_dotenv
from starlette.middleware.cors import CORSMiddleware
from motor.motor_asyncio import AsyncIOMotorClient
import os
import logging
from pathlib import Path
from pydantic import BaseModel, Field, ConfigDict, EmailStr
from typing import List, Optional, Dict
import uuid
from datetime import datetime, timezone, timedelta
import bcrypt
import jwt
import qrcode
import io
import base64
from emergentintegrations.payments.stripe.checkout import (
    StripeCheckout,
    CheckoutSessionResponse,
    CheckoutStatusResponse,
    CheckoutSessionRequest
)

ROOT_DIR = Path(__file__).parent
load_dotenv(ROOT_DIR / '.env')

# MongoDB connection
mongo_url = os.environ['MONGO_URL']
client = AsyncIOMotorClient(mongo_url)
db = client[os.environ['DB_NAME']]

# JWT Configuration
JWT_SECRET = os.environ['JWT_SECRET_KEY']
JWT_ALGORITHM = os.environ['JWT_ALGORITHM']
JWT_EXPIRATION = int(os.environ['JWT_EXPIRATION_HOURS'])

# Stripe Configuration
STRIPE_API_KEY = os.environ['STRIPE_API_KEY']

# Create the main app
app = FastAPI()
api_router = APIRouter(prefix="/api")
security = HTTPBearer()

# ============ MODELS ============

class User(BaseModel):
    model_config = ConfigDict(extra="ignore")
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    email: EmailStr
    full_name: str
    phone: str
    password_hash: str
    created_at: str = Field(default_factory=lambda: datetime.now(timezone.utc).isoformat())

class UserRegister(BaseModel):
    email: EmailStr
    password: str
    full_name: str
    phone: str

class UserLogin(BaseModel):
    email: EmailStr
    password: str

class TokenResponse(BaseModel):
    token: str
    user: Dict

class Event(BaseModel):
    model_config = ConfigDict(extra="ignore")
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    name: str
    date: str
    venue: str
    description: str
    image_url: str
    status: str = "active"
    created_at: str = Field(default_factory=lambda: datetime.now(timezone.utc).isoformat())

class TicketCategory(BaseModel):
    model_config = ConfigDict(extra="ignore")
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    event_id: str
    category: str
    price: float
    total_seats: int
    available_seats: int
    benefits: List[str]
    created_at: str = Field(default_factory=lambda: datetime.now(timezone.utc).isoformat())

class Seat(BaseModel):
    model_config = ConfigDict(extra="ignore")
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    event_id: str
    category: str
    seat_number: str
    row: str
    status: str = "available"  # available, reserved, booked
    reserved_until: Optional[str] = None

class Booking(BaseModel):
    model_config = ConfigDict(extra="ignore")
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    user_id: str
    event_id: str
    seats: List[str]
    total_price: float
    status: str = "pending"  # pending, confirmed, cancelled
    payment_session_id: Optional[str] = None
    qr_code: Optional[str] = None
    booking_date: str = Field(default_factory=lambda: datetime.now(timezone.utc).isoformat())

class PaymentTransaction(BaseModel):
    model_config = ConfigDict(extra="ignore")
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    booking_id: str
    user_id: str
    session_id: str
    amount: float
    currency: str = "usd"
    payment_status: str = "pending"
    metadata: Optional[Dict] = None
    created_at: str = Field(default_factory=lambda: datetime.now(timezone.utc).isoformat())

class SeatReservation(BaseModel):
    seats: List[str]

class CheckoutRequest(BaseModel):
    booking_id: str
    origin_url: str

# ============ HELPER FUNCTIONS ============

def hash_password(password: str) -> str:
    return bcrypt.hashpw(password.encode('utf-8'), bcrypt.gensalt()).decode('utf-8')

def verify_password(password: str, hashed: str) -> bool:
    return bcrypt.checkpw(password.encode('utf-8'), hashed.encode('utf-8'))

def create_jwt_token(user_id: str, email: str) -> str:
    expiration = datetime.now(timezone.utc) + timedelta(hours=JWT_EXPIRATION)
    payload = {
        "user_id": user_id,
        "email": email,
        "exp": expiration
    }
    return jwt.encode(payload, JWT_SECRET, algorithm=JWT_ALGORITHM)

def verify_jwt_token(token: str) -> Dict:
    try:
        payload = jwt.decode(token, JWT_SECRET, algorithms=[JWT_ALGORITHM])
        return payload
    except jwt.ExpiredSignatureError:
        raise HTTPException(status_code=401, detail="Token expired")
    except jwt.InvalidTokenError:
        raise HTTPException(status_code=401, detail="Invalid token")

async def get_current_user(credentials: HTTPAuthorizationCredentials = Depends(security)):
    token = credentials.credentials
    payload = verify_jwt_token(token)
    user = await db.users.find_one({"id": payload["user_id"]}, {"_id": 0})
    if not user:
        raise HTTPException(status_code=404, detail="User not found")
    return user

def generate_qr_code(booking_id: str) -> str:
    qr = qrcode.QRCode(version=1, box_size=10, border=5)
    qr.add_data(f"FEAST-TICKET-{booking_id}")
    qr.make(fit=True)
    img = qr.make_image(fill_color="black", back_color="white")
    
    buffer = io.BytesIO()
    img.save(buffer, format="PNG")
    buffer.seek(0)
    img_str = base64.b64encode(buffer.getvalue()).decode()
    return f"data:image/png;base64,{img_str}"

# ============ AUTH ROUTES ============

@api_router.post("/auth/register", response_model=TokenResponse)
async def register(user_data: UserRegister):
    # Check if user exists
    existing_user = await db.users.find_one({"email": user_data.email})
    if existing_user:
        raise HTTPException(status_code=400, detail="Email already registered")
    
    # Create user
    password_hash = hash_password(user_data.password)
    user = User(
        email=user_data.email,
        full_name=user_data.full_name,
        phone=user_data.phone,
        password_hash=password_hash
    )
    
    await db.users.insert_one(user.model_dump())
    
    # Generate token
    token = create_jwt_token(user.id, user.email)
    
    user_dict = user.model_dump()
    del user_dict['password_hash']
    
    return {"token": token, "user": user_dict}

@api_router.post("/auth/login", response_model=TokenResponse)
async def login(credentials: UserLogin):
    user = await db.users.find_one({"email": credentials.email}, {"_id": 0})
    if not user:
        raise HTTPException(status_code=401, detail="Invalid credentials")
    
    if not verify_password(credentials.password, user["password_hash"]):
        raise HTTPException(status_code=401, detail="Invalid credentials")
    
    token = create_jwt_token(user["id"], user["email"])
    
    del user['password_hash']
    
    return {"token": token, "user": user}

@api_router.get("/auth/me")
async def get_me(current_user: Dict = Depends(get_current_user)):
    return current_user

# ============ EVENT ROUTES ============

@api_router.get("/events", response_model=List[Event])
async def get_events():
    events = await db.events.find({"status": "active"}, {"_id": 0}).to_list(100)
    return events

@api_router.get("/events/{event_id}", response_model=Event)
async def get_event(event_id: str):
    event = await db.events.find_one({"id": event_id}, {"_id": 0})
    if not event:
        raise HTTPException(status_code=404, detail="Event not found")
    return event

# ============ TICKET ROUTES ============

@api_router.get("/events/{event_id}/tickets", response_model=List[TicketCategory])
async def get_event_tickets(event_id: str):
    tickets = await db.ticket_categories.find({"event_id": event_id}, {"_id": 0}).to_list(100)
    return tickets

# ============ SEAT ROUTES ============

@api_router.get("/events/{event_id}/seats/{category}")
async def get_seats(event_id: str, category: str):
    seats = await db.seats.find(
        {"event_id": event_id, "category": category},
        {"_id": 0}
    ).to_list(500)
    
    # Check and release expired reservations
    now = datetime.now(timezone.utc)
    for seat in seats:
        if seat["status"] == "reserved" and seat.get("reserved_until"):
            reserved_until = datetime.fromisoformat(seat["reserved_until"])
            if now > reserved_until:
                await db.seats.update_one(
                    {"id": seat["id"]},
                    {"$set": {"status": "available", "reserved_until": None}}
                )
                seat["status"] = "available"
                seat["reserved_until"] = None
    
    return seats

@api_router.post("/seats/reserve")
async def reserve_seats(reservation: SeatReservation, current_user: Dict = Depends(get_current_user)):
    # Reserve seats for 15 minutes
    reserved_until = datetime.now(timezone.utc) + timedelta(minutes=15)
    
    for seat_id in reservation.seats:
        seat = await db.seats.find_one({"id": seat_id})
        if not seat or seat["status"] != "available":
            raise HTTPException(status_code=400, detail=f"Seat {seat_id} is not available")
        
        await db.seats.update_one(
            {"id": seat_id},
            {"$set": {"status": "reserved", "reserved_until": reserved_until.isoformat()}}
        )
    
    return {"success": True, "reserved_until": reserved_until.isoformat()}

# ============ BOOKING ROUTES ============

@api_router.post("/bookings", response_model=Booking)
async def create_booking(
    event_id: str = Query(...),
    seats: List[str] = Query(...),
    current_user: Dict = Depends(get_current_user)
):
    # Calculate total price
    total_price = 0.0
    seat_objects = []
    
    for seat_id in seats:
        seat = await db.seats.find_one({"id": seat_id})
        if not seat:
            raise HTTPException(status_code=404, detail=f"Seat {seat_id} not found")
        
        # Get ticket category price
        ticket = await db.ticket_categories.find_one({
            "event_id": event_id,
            "category": seat["category"]
        })
        if not ticket:
            raise HTTPException(status_code=404, detail="Ticket category not found")
        
        total_price += ticket["price"]
        seat_objects.append(seat)
    
    # Create booking
    booking = Booking(
        user_id=current_user["id"],
        event_id=event_id,
        seats=seats,
        total_price=total_price,
        status="pending"
    )
    
    await db.bookings.insert_one(booking.model_dump())
    
    return booking

@api_router.get("/bookings/my", response_model=List[Booking])
async def get_my_bookings(current_user: Dict = Depends(get_current_user)):
    bookings = await db.bookings.find(
        {"user_id": current_user["id"]},
        {"_id": 0}
    ).to_list(100)
    return bookings

@api_router.get("/bookings/{booking_id}", response_model=Booking)
async def get_booking(booking_id: str, current_user: Dict = Depends(get_current_user)):
    booking = await db.bookings.find_one({"id": booking_id, "user_id": current_user["id"]}, {"_id": 0})
    if not booking:
        raise HTTPException(status_code=404, detail="Booking not found")
    return booking

# ============ PAYMENT ROUTES ============

@api_router.post("/payment/checkout")
async def create_checkout_session(
    checkout_req: CheckoutRequest,
    current_user: Dict = Depends(get_current_user)
):
    # Get booking
    booking = await db.bookings.find_one({"id": checkout_req.booking_id, "user_id": current_user["id"]})
    if not booking:
        raise HTTPException(status_code=404, detail="Booking not found")
    
    if booking["status"] == "confirmed":
        raise HTTPException(status_code=400, detail="Booking already confirmed")
    
    # Initialize Stripe
    host_url = checkout_req.origin_url
    webhook_url = f"{host_url}/api/webhook/stripe"
    stripe_checkout = StripeCheckout(api_key=STRIPE_API_KEY, webhook_url=webhook_url)
    
    # Create checkout session
    success_url = f"{host_url}/payment/success?session_id={{{{CHECKOUT_SESSION_ID}}}}"
    cancel_url = f"{host_url}/payment/cancel"
    
    checkout_request = CheckoutSessionRequest(
        amount=float(booking["total_price"]),
        currency="usd",
        success_url=success_url,
        cancel_url=cancel_url,
        metadata={
            "booking_id": booking["id"],
            "user_id": current_user["id"],
            "event_id": booking["event_id"]
        }
    )
    
    session: CheckoutSessionResponse = await stripe_checkout.create_checkout_session(checkout_request)
    
    # Create payment transaction
    payment = PaymentTransaction(
        booking_id=booking["id"],
        user_id=current_user["id"],
        session_id=session.session_id,
        amount=booking["total_price"],
        currency="usd",
        payment_status="pending",
        metadata={
            "booking_id": booking["id"],
            "event_id": booking["event_id"]
        }
    )
    
    await db.payment_transactions.insert_one(payment.model_dump())
    
    # Update booking with session ID
    await db.bookings.update_one(
        {"id": booking["id"]},
        {"$set": {"payment_session_id": session.session_id}}
    )
    
    return {"url": session.url, "session_id": session.session_id}

@api_router.get("/payment/status/{session_id}")
async def check_payment_status(
    session_id: str,
    current_user: Dict = Depends(get_current_user)
):
    # Get payment transaction
    payment = await db.payment_transactions.find_one({"session_id": session_id})
    if not payment:
        raise HTTPException(status_code=404, detail="Payment not found")
    
    # Check if already processed
    if payment["payment_status"] == "paid":
        return {"status": "complete", "payment_status": "paid"}
    
    # Initialize Stripe and check status
    webhook_url = ""  # Not needed for status check
    stripe_checkout = StripeCheckout(api_key=STRIPE_API_KEY, webhook_url=webhook_url)
    
    status: CheckoutStatusResponse = await stripe_checkout.get_checkout_status(session_id)
    
    # Update payment transaction
    await db.payment_transactions.update_one(
        {"session_id": session_id},
        {"$set": {"payment_status": status.payment_status}}
    )
    
    # If payment is complete, update booking and seats
    if status.payment_status == "paid":
        booking = await db.bookings.find_one({"id": payment["booking_id"]})
        
        if booking and booking["status"] != "confirmed":
            # Generate QR code
            qr_code = generate_qr_code(booking["id"])
            
            # Update booking
            await db.bookings.update_one(
                {"id": booking["id"]},
                {"$set": {"status": "confirmed", "qr_code": qr_code}}
            )
            
            # Update seats to booked
            for seat_id in booking["seats"]:
                await db.seats.update_one(
                    {"id": seat_id},
                    {"$set": {"status": "booked", "reserved_until": None}}
                )
            
            # Update ticket availability
            for seat_id in booking["seats"]:
                seat = await db.seats.find_one({"id": seat_id})
                await db.ticket_categories.update_one(
                    {"event_id": booking["event_id"], "category": seat["category"]},
                    {"$inc": {"available_seats": -1}}
                )
    
    return {
        "status": status.status,
        "payment_status": status.payment_status,
        "amount_total": status.amount_total,
        "currency": status.currency
    }

@api_router.post("/webhook/stripe")
async def stripe_webhook(request: Request, stripe_signature: str = Header(None)):
    body = await request.body()
    
    webhook_url = ""  # Not needed for webhook handling
    stripe_checkout = StripeCheckout(api_key=STRIPE_API_KEY, webhook_url=webhook_url)
    
    try:
        webhook_response = await stripe_checkout.handle_webhook(body, stripe_signature)
        
        # Update payment status
        await db.payment_transactions.update_one(
            {"session_id": webhook_response.session_id},
            {"$set": {"payment_status": webhook_response.payment_status}}
        )
        
        return {"status": "success"}
    except Exception as e:
        logging.error(f"Webhook error: {e}")
        raise HTTPException(status_code=400, detail=str(e))

# ============ ADMIN ROUTES ============

@api_router.get("/admin/bookings")
async def get_all_bookings(current_user: Dict = Depends(get_current_user)):
    bookings = await db.bookings.find({}, {"_id": 0}).to_list(1000)
    return bookings

@api_router.get("/admin/stats")
async def get_stats(current_user: Dict = Depends(get_current_user)):
    total_bookings = await db.bookings.count_documents({})
    confirmed_bookings = await db.bookings.count_documents({"status": "confirmed"})
    total_revenue = 0.0
    
    confirmed = await db.bookings.find({"status": "confirmed"}, {"_id": 0}).to_list(1000)
    for booking in confirmed:
        total_revenue += booking["total_price"]
    
    return {
        "total_bookings": total_bookings,
        "confirmed_bookings": confirmed_bookings,
        "total_revenue": total_revenue
    }

# ============ INITIAL DATA SETUP ============

@api_router.post("/init-data")
async def initialize_data():
    # Check if data already exists
    existing_event = await db.events.find_one({})
    if existing_event:
        return {"message": "Data already initialized"}
    
    # Create Feast event
    event = Event(
        name="Feast Live in Concert 2024",
        date="2024-12-31T20:00:00Z",
        venue="Jakarta International Expo",
        description="Join us for an unforgettable night with Feast! Experience their greatest hits and new releases live on stage. Don't miss this spectacular show!",
        image_url="https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?w=800",
        status="active"
    )
    await db.events.insert_one(event.model_dump())
    
    # Create ticket categories
    categories = [
        {
            "category": "VIP",
            "price": 150.00,
            "total_seats": 50,
            "available_seats": 50,
            "benefits": ["Premium seating", "Meet & Greet", "Exclusive merchandise", "VIP lounge access"]
        },
        {
            "category": "Regular",
            "price": 75.00,
            "total_seats": 100,
            "available_seats": 100,
            "benefits": ["Reserved seating", "Good view of stage", "Access to concessions"]
        },
        {
            "category": "Standing",
            "price": 50.00,
            "total_seats": 200,
            "available_seats": 200,
            "benefits": ["General admission", "Standing area", "Close to stage"]
        }
    ]
    
    for cat in categories:
        ticket = TicketCategory(
            event_id=event.id,
            **cat
        )
        await db.ticket_categories.insert_one(ticket.model_dump())
    
    # Create seats
    # VIP seats
    for row in range(1, 6):
        for num in range(1, 11):
            seat = Seat(
                event_id=event.id,
                category="VIP",
                seat_number=str(num),
                row=f"VIP-{row}"
            )
            await db.seats.insert_one(seat.model_dump())
    
    # Regular seats
    for row in range(1, 11):
        for num in range(1, 11):
            seat = Seat(
                event_id=event.id,
                category="Regular",
                seat_number=str(num),
                row=f"R-{row}"
            )
            await db.seats.insert_one(seat.model_dump())
    
    # Standing area (no specific seats)
    for i in range(1, 201):
        seat = Seat(
            event_id=event.id,
            category="Standing",
            seat_number=str(i),
            row="STANDING"
        )
        await db.seats.insert_one(seat.model_dump())
    
    return {"message": "Data initialized successfully", "event_id": event.id}

# Include router
app.include_router(api_router)

app.add_middleware(
    CORSMiddleware,
    allow_credentials=True,
    allow_origins=os.environ.get('CORS_ORIGINS', '*').split(','),
    allow_methods=["*"],
    allow_headers=["*"],
)

logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

@app.on_event("shutdown")
async def shutdown_db_client():
    client.close()