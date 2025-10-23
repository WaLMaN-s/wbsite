import { useState, useEffect } from "react";
import { useNavigate, useParams, useSearchParams } from "react-router-dom";
import { api } from "../App";
import { Button } from "@/components/ui/button";
import { ArrowLeft, Music } from "lucide-react";
import { toast } from "sonner";

const SeatSelection = ({ user }) => {
  const { eventId } = useParams();
  const [searchParams] = useSearchParams();
  const category = searchParams.get("category");
  const [seats, setSeats] = useState([]);
  const [selectedSeats, setSelectedSeats] = useState([]);
  const [loading, setLoading] = useState(false);
  const [ticketPrice, setTicketPrice] = useState(0);
  const navigate = useNavigate();

  useEffect(() => {
    loadSeats();
    loadTicketPrice();
  }, [eventId, category]);

  const loadSeats = async () => {
    try {
      const response = await api.get(`/events/${eventId}/seats/${category}`);
      setSeats(response.data);
    } catch (error) {
      toast.error("Failed to load seats");
    }
  };

  const loadTicketPrice = async () => {
    try {
      const response = await api.get(`/events/${eventId}/tickets`);
      const ticket = response.data.find(t => t.category === category);
      if (ticket) {
        setTicketPrice(ticket.price);
      }
    } catch (error) {
      console.error("Failed to load ticket price");
    }
  };

  const toggleSeatSelection = (seatId) => {
    if (selectedSeats.includes(seatId)) {
      setSelectedSeats(selectedSeats.filter(id => id !== seatId));
    } else {
      setSelectedSeats([...selectedSeats, seatId]);
    }
  };

  const handleReserveSeats = async () => {
    if (selectedSeats.length === 0) {
      toast.error("Please select at least one seat");
      return;
    }

    setLoading(true);
    try {
      // Reserve seats
      await api.post("/seats/reserve", { seats: selectedSeats });
      
      // Create booking
      const bookingResponse = await api.post(
        `/bookings?event_id=${eventId}&seats=${selectedSeats.join(',')}`
      );
      
      // Navigate to payment
      const originUrl = window.location.origin;
      const checkoutResponse = await api.post("/payment/checkout", {
        booking_id: bookingResponse.data.id,
        origin_url: originUrl
      });
      
      // Redirect to Stripe
      window.location.href = checkoutResponse.data.url;
    } catch (error) {
      toast.error(error.response?.data?.detail || "Failed to reserve seats");
      setLoading(false);
    }
  };

  // Group seats by row
  const groupedSeats = seats.reduce((acc, seat) => {
    if (!acc[seat.row]) {
      acc[seat.row] = [];
    }
    acc[seat.row].push(seat);
    return acc;
  }, {});

  const totalPrice = selectedSeats.length * ticketPrice;

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900">
      {/* Navigation */}
      <nav className="fixed top-0 left-0 right-0 z-50 bg-black/30 backdrop-blur-xl border-b border-white/10">
        <div className="max-w-7xl mx-auto px-6 py-4">
          <div className="flex items-center gap-4">
            <Button
              data-testid="back-to-event-btn"
              onClick={() => navigate(`/event/${eventId}`)}
              variant="ghost"
              className="text-white hover:bg-white/10"
            >
              <ArrowLeft className="w-5 h-5 mr-2" />
              Back
            </Button>
            <div className="flex items-center gap-3">
              <Music className="w-8 h-8 text-indigo-400" />
              <h1 className="text-2xl font-bold text-white">Select Your Seats</h1>
            </div>
          </div>
        </div>
      </nav>

      <div className="pt-32 pb-20 px-6">
        <div className="max-w-6xl mx-auto">
          {/* Category Info */}
          <div className="glass-card p-6 mb-8 text-center animate-fadeIn">
            <h2 className="text-3xl font-bold text-white mb-2">{category} Section</h2>
            <p className="text-gray-300">${ticketPrice.toFixed(2)} per seat</p>
          </div>

          {/* Legend */}
          <div className="flex justify-center gap-8 mb-8 animate-fadeIn">
            <div className="flex items-center gap-2">
              <div className="seat available w-10 h-10" />
              <span className="text-gray-300">Available</span>
            </div>
            <div className="flex items-center gap-2">
              <div className="seat selected w-10 h-10" />
              <span className="text-gray-300">Selected</span>
            </div>
            <div className="flex items-center gap-2">
              <div className="seat booked w-10 h-10" />
              <span className="text-gray-300">Booked</span>
            </div>
          </div>

          {/* Stage */}
          <div className="mb-12 animate-fadeIn">
            <div className="bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-center py-4 rounded-t-3xl font-bold text-xl">
              STAGE
            </div>
          </div>

          {/* Seats */}
          <div className="space-y-6 mb-8">
            {Object.entries(groupedSeats)
              .sort((a, b) => {
                if (category === "Standing") return 0;
                const aNum = parseInt(a[0].split('-')[1]) || 0;
                const bNum = parseInt(b[0].split('-')[1]) || 0;
                return aNum - bNum;
              })
              .map(([row, rowSeats]) => (
                <div key={row} className="animate-fadeIn">
                  <div className="text-gray-400 text-sm mb-2 font-semibold">{row}</div>
                  <div className="flex flex-wrap gap-2">
                    {rowSeats
                      .sort((a, b) => parseInt(a.seat_number) - parseInt(b.seat_number))
                      .map((seat) => (
                        <div
                          key={seat.id}
                          data-testid={`seat-${seat.row}-${seat.seat_number}`}
                          className={`seat ${
                            seat.status === 'available' && !selectedSeats.includes(seat.id)
                              ? 'available'
                              : selectedSeats.includes(seat.id)
                              ? 'selected'
                              : seat.status === 'booked'
                              ? 'booked'
                              : 'reserved'
                          }`}
                          onClick={() => {
                            if (seat.status === 'available') {
                              toggleSeatSelection(seat.id);
                            }
                          }}
                        >
                          {seat.seat_number}
                        </div>
                      ))}
                  </div>
                </div>
              ))}
          </div>

          {/* Checkout Section */}
          {selectedSeats.length > 0 && (
            <div className="glass-card p-6 sticky bottom-6 animate-fadeIn">
              <div className="flex items-center justify-between">
                <div>
                  <div className="text-white font-semibold">
                    <span data-testid="selected-seats-count">{selectedSeats.length}</span> seat(s) selected
                  </div>
                  <div className="text-2xl font-bold gradient-text" data-testid="total-price">
                    Total: ${totalPrice.toFixed(2)}
                  </div>
                </div>
                <Button
                  data-testid="proceed-to-payment-btn"
                  onClick={handleReserveSeats}
                  disabled={loading}
                  className="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-lg px-8 py-6"
                >
                  {loading ? "Processing..." : "Proceed to Payment"}
                </Button>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default SeatSelection;