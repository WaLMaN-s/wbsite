import requests
import sys
import json
from datetime import datetime
import time

class FeastTicketAPITester:
    def __init__(self, base_url="https://feast-live-tickets.preview.emergentagent.com"):
        self.base_url = base_url
        self.api_url = f"{base_url}/api"
        self.token = None
        self.user_id = None
        self.event_id = None
        self.booking_id = None
        self.tests_run = 0
        self.tests_passed = 0
        self.test_results = []

    def log_test(self, name, success, details=""):
        """Log test result"""
        self.tests_run += 1
        if success:
            self.tests_passed += 1
            print(f"✅ {name} - PASSED")
        else:
            print(f"❌ {name} - FAILED: {details}")
        
        self.test_results.append({
            "test": name,
            "success": success,
            "details": details
        })

    def run_test(self, name, method, endpoint, expected_status, data=None, headers=None):
        """Run a single API test"""
        url = f"{self.api_url}/{endpoint}"
        test_headers = {'Content-Type': 'application/json'}
        
        if self.token:
            test_headers['Authorization'] = f'Bearer {self.token}'
        
        if headers:
            test_headers.update(headers)

        try:
            if method == 'GET':
                response = requests.get(url, headers=test_headers, timeout=10)
            elif method == 'POST':
                response = requests.post(url, json=data, headers=test_headers, timeout=10)
            elif method == 'PUT':
                response = requests.put(url, json=data, headers=test_headers, timeout=10)
            elif method == 'DELETE':
                response = requests.delete(url, headers=test_headers, timeout=10)

            success = response.status_code == expected_status
            
            if success:
                try:
                    response_data = response.json()
                    self.log_test(name, True)
                    return True, response_data
                except:
                    self.log_test(name, True)
                    return True, {}
            else:
                error_msg = f"Expected {expected_status}, got {response.status_code}"
                try:
                    error_detail = response.json().get('detail', '')
                    if error_detail:
                        error_msg += f" - {error_detail}"
                except:
                    pass
                self.log_test(name, False, error_msg)
                return False, {}

        except Exception as e:
            self.log_test(name, False, f"Request failed: {str(e)}")
            return False, {}

    def test_init_data(self):
        """Initialize test data"""
        print("\n🔧 Initializing test data...")
        success, response = self.run_test(
            "Initialize Data",
            "POST",
            "init-data",
            200
        )
        return success

    def test_get_events(self):
        """Test getting events"""
        print("\n📅 Testing Events API...")
        success, response = self.run_test(
            "Get Events",
            "GET",
            "events",
            200
        )
        
        if success and response:
            if len(response) > 0:
                self.event_id = response[0]['id']
                self.log_test("Event ID Retrieved", True, f"Event ID: {self.event_id}")
                return True
            else:
                self.log_test("Event ID Retrieved", False, "No events found")
                return False
        return success

    def test_get_event_details(self):
        """Test getting specific event details"""
        if not self.event_id:
            self.log_test("Get Event Details", False, "No event ID available")
            return False
            
        success, response = self.run_test(
            "Get Event Details",
            "GET",
            f"events/{self.event_id}",
            200
        )
        return success

    def test_get_event_tickets(self):
        """Test getting event tickets"""
        if not self.event_id:
            self.log_test("Get Event Tickets", False, "No event ID available")
            return False
            
        success, response = self.run_test(
            "Get Event Tickets",
            "GET",
            f"events/{self.event_id}/tickets",
            200
        )
        return success

    def test_user_registration(self):
        """Test user registration"""
        print("\n👤 Testing Authentication...")
        timestamp = datetime.now().strftime('%H%M%S')
        test_user_data = {
            "email": f"test_user_{timestamp}@example.com",
            "password": "TestPass123!",
            "full_name": "Test User",
            "phone": "+1234567890"
        }
        
        success, response = self.run_test(
            "User Registration",
            "POST",
            "auth/register",
            200,
            data=test_user_data
        )
        
        if success and response:
            self.token = response.get('token')
            self.user_id = response.get('user', {}).get('id')
            if self.token and self.user_id:
                self.log_test("Token Retrieved", True)
                return True
            else:
                self.log_test("Token Retrieved", False, "No token in response")
                return False
        return success

    def test_user_login(self):
        """Test user login with existing credentials"""
        timestamp = datetime.now().strftime('%H%M%S')
        login_data = {
            "email": f"test_user_{timestamp}@example.com",
            "password": "TestPass123!"
        }
        
        success, response = self.run_test(
            "User Login",
            "POST",
            "auth/login",
            200,
            data=login_data
        )
        return success

    def test_get_me(self):
        """Test getting current user info"""
        if not self.token:
            self.log_test("Get Current User", False, "No token available")
            return False
            
        success, response = self.run_test(
            "Get Current User",
            "GET",
            "auth/me",
            200
        )
        return success

    def test_get_seats(self):
        """Test getting seats for different categories"""
        if not self.event_id:
            self.log_test("Get Seats", False, "No event ID available")
            return False
            
        categories = ["VIP", "Regular", "Standing"]
        all_success = True
        
        for category in categories:
            success, response = self.run_test(
                f"Get {category} Seats",
                "GET",
                f"events/{self.event_id}/seats/{category}",
                200
            )
            if not success:
                all_success = False
                
        return all_success

    def test_seat_reservation(self):
        """Test seat reservation"""
        if not self.event_id or not self.token:
            self.log_test("Seat Reservation", False, "Missing event ID or token")
            return False
            
        # First get available seats
        success, seats_response = self.run_test(
            "Get VIP Seats for Reservation",
            "GET",
            f"events/{self.event_id}/seats/VIP",
            200
        )
        
        if not success or not seats_response:
            return False
            
        # Find available seats
        available_seats = [seat['id'] for seat in seats_response if seat['status'] == 'available'][:2]
        
        if not available_seats:
            self.log_test("Seat Reservation", False, "No available seats found")
            return False
            
        # Reserve seats
        reservation_data = {"seats": available_seats}
        success, response = self.run_test(
            "Reserve Seats",
            "POST",
            "seats/reserve",
            200,
            data=reservation_data
        )
        
        return success

    def test_create_booking(self):
        """Test creating a booking"""
        if not self.event_id or not self.token:
            self.log_test("Create Booking", False, "Missing event ID or token")
            return False
            
        # Get available seats first
        success, seats_response = self.run_test(
            "Get Seats for Booking",
            "GET",
            f"events/{self.event_id}/seats/VIP",
            200
        )
        
        if not success or not seats_response:
            return False
            
        available_seats = [seat['id'] for seat in seats_response if seat['status'] == 'available'][:1]
        
        if not available_seats:
            self.log_test("Create Booking", False, "No available seats for booking")
            return False
            
        # Create booking with proper query parameters
        booking_url = f"bookings?event_id={self.event_id}"
        for seat in available_seats:
            booking_url += f"&seats={seat}"
            
        success, response = self.run_test(
            "Create Booking",
            "POST",
            booking_url,
            200
        )
        
        if success and response:
            self.booking_id = response.get('id')
            if self.booking_id:
                self.log_test("Booking ID Retrieved", True, f"Booking ID: {self.booking_id}")
                return True
            else:
                self.log_test("Booking ID Retrieved", False, "No booking ID in response")
                return False
        return success

    def test_get_my_bookings(self):
        """Test getting user's bookings"""
        if not self.token:
            self.log_test("Get My Bookings", False, "No token available")
            return False
            
        success, response = self.run_test(
            "Get My Bookings",
            "GET",
            "bookings/my",
            200
        )
        return success

    def test_get_booking_details(self):
        """Test getting specific booking details"""
        if not self.booking_id or not self.token:
            self.log_test("Get Booking Details", False, "Missing booking ID or token")
            return False
            
        success, response = self.run_test(
            "Get Booking Details",
            "GET",
            f"bookings/{self.booking_id}",
            200
        )
        return success

    def test_payment_checkout(self):
        """Test payment checkout session creation"""
        if not self.booking_id or not self.token:
            self.log_test("Payment Checkout", False, "Missing booking ID or token")
            return False
            
        checkout_data = {
            "booking_id": self.booking_id,
            "origin_url": self.base_url
        }
        
        success, response = self.run_test(
            "Create Payment Checkout",
            "POST",
            "payment/checkout",
            200,
            data=checkout_data
        )
        
        if success and response:
            if 'url' in response and 'session_id' in response:
                self.log_test("Checkout URL Generated", True)
                return True
            else:
                self.log_test("Checkout URL Generated", False, "Missing URL or session_id")
                return False
        return success

    def test_admin_endpoints(self):
        """Test admin endpoints"""
        print("\n🔧 Testing Admin Endpoints...")
        if not self.token:
            self.log_test("Admin Stats", False, "No token available")
            self.log_test("Admin Bookings", False, "No token available")
            return False
            
        # Test admin stats
        stats_success, _ = self.run_test(
            "Admin Stats",
            "GET",
            "admin/stats",
            200
        )
        
        # Test admin bookings
        bookings_success, _ = self.run_test(
            "Admin Bookings",
            "GET",
            "admin/bookings",
            200
        )
        
        return stats_success and bookings_success

    def run_all_tests(self):
        """Run all API tests"""
        print("🚀 Starting Feast Concert Ticket API Tests")
        print(f"🌐 Testing against: {self.base_url}")
        print("=" * 60)
        
        # Initialize data first
        self.test_init_data()
        
        # Test events
        self.test_get_events()
        self.test_get_event_details()
        self.test_get_event_tickets()
        
        # Test authentication
        self.test_user_registration()
        self.test_get_me()
        
        # Test seats and reservations
        self.test_get_seats()
        self.test_seat_reservation()
        
        # Test bookings
        self.test_create_booking()
        self.test_get_my_bookings()
        self.test_get_booking_details()
        
        # Test payment
        self.test_payment_checkout()
        
        # Test admin endpoints
        self.test_admin_endpoints()
        
        # Print summary
        print("\n" + "=" * 60)
        print(f"📊 Test Results: {self.tests_passed}/{self.tests_run} tests passed")
        
        if self.tests_passed == self.tests_run:
            print("🎉 All tests passed!")
            return 0
        else:
            print(f"⚠️  {self.tests_run - self.tests_passed} tests failed")
            return 1

def main():
    tester = FeastTicketAPITester()
    return tester.run_all_tests()

if __name__ == "__main__":
    sys.exit(main())