import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { api } from "../App";
import { Button } from "@/components/ui/button";
import { ArrowLeft, Music, Download, Calendar, MapPin } from "lucide-react";
import { toast } from "sonner";

const MyTickets = ({ user }) => {
  const [bookings, setBookings] = useState([]);
  const [events, setEvents] = useState({});
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  useEffect(() => {
    loadBookings();
  }, []);

  const loadBookings = async () => {
    try {
      const response = await api.get("/bookings/my");
      setBookings(response.data);
      
      // Load event details
      const eventIds = [...new Set(response.data.map(b => b.event_id))];
      const eventPromises = eventIds.map(id => api.get(`/events/${id}`));
      const eventResponses = await Promise.all(eventPromises);
      
      const eventsMap = {};
      eventResponses.forEach(res => {
        eventsMap[res.data.id] = res.data;
      });
      setEvents(eventsMap);
    } catch (error) {
      toast.error("Failed to load tickets");
    } finally {
      setLoading(false);
    }
  };

  const downloadTicket = (booking) => {
    if (!booking.qr_code) return;
    
    // Create a simple ticket HTML
    const event = events[booking.event_id];
    const ticketHTML = `
      <!DOCTYPE html>
      <html>
        <head>
          <title>Ticket - ${event?.name}</title>
          <style>
            body { font-family: Arial, sans-serif; padding: 40px; background: #f5f5f5; }
            .ticket { max-width: 600px; margin: 0 auto; background: white; padding: 40px; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
            h1 { color: #4f46e5; margin-bottom: 10px; }
            .info { margin: 20px 0; }
            .label { color: #666; font-size: 14px; }
            .value { color: #000; font-size: 18px; font-weight: bold; margin-bottom: 15px; }
            .qr { text-align: center; margin: 30px 0; }
            .qr img { max-width: 300px; }
            .booking-id { background: #f0f0f0; padding: 10px; border-radius: 8px; text-align: center; margin: 20px 0; }
          </style>
        </head>
        <body>
          <div class="ticket">
            <h1>FEAST CONCERT TICKET</h1>
            <div class="booking-id">
              <div class="label">Booking ID</div>
              <div class="value">${booking.id}</div>
            </div>
            <div class="info">
              <div class="label">Event</div>
              <div class="value">${event?.name}</div>
            </div>
            <div class="info">
              <div class="label">Date</div>
              <div class="value">${new Date(event?.date).toLocaleDateString()}</div>
            </div>
            <div class="info">
              <div class="label">Venue</div>
              <div class="value">${event?.venue}</div>
            </div>
            <div class="info">
              <div class="label">Number of Seats</div>
              <div class="value">${booking.seats.length}</div>
            </div>
            <div class="info">
              <div class="label">Total Price</div>
              <div class="value">$${booking.total_price.toFixed(2)}</div>
            </div>
            <div class="qr">
              <img src="${booking.qr_code}" alt="QR Code" />
            </div>
            <p style="text-align: center; color: #666; font-size: 14px;">Please present this QR code at the venue entrance</p>
          </div>
        </body>
      </html>
    `;
    
    const blob = new Blob([ticketHTML], { type: 'text/html' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `feast-ticket-${booking.id}.html`;
    a.click();
    URL.revokeObjectURL(url);
    
    toast.success("Ticket downloaded!");
  };

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900">
        <div className="spinner" />
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900">
      {/* Navigation */}
      <nav className="fixed top-0 left-0 right-0 z-50 bg-black/30 backdrop-blur-xl border-b border-white/10">
        <div className="max-w-7xl mx-auto px-6 py-4">
          <div className="flex items-center gap-4">
            <Button
              data-testid="back-home-btn"
              onClick={() => navigate("/")}
              variant="ghost"
              className="text-white hover:bg-white/10"
            >
              <ArrowLeft className="w-5 h-5 mr-2" />
              Back
            </Button>
            <div className="flex items-center gap-3">
              <Music className="w-8 h-8 text-indigo-400" />
              <h1 className="text-2xl font-bold text-white">My Tickets</h1>
            </div>
          </div>
        </div>
      </nav>

      <div className="pt-32 pb-20 px-6">
        <div className="max-w-6xl mx-auto">
          {bookings.length === 0 ? (
            <div className="glass-card p-12 text-center animate-fadeIn">
              <div className="text-gray-400 text-lg mb-4">No tickets found</div>
              <Button
                onClick={() => navigate("/")}
                className="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white"
              >
                Browse Events
              </Button>
            </div>
          ) : (
            <div className="grid gap-6">
              {bookings.map((booking, index) => {
                const event = events[booking.event_id];
                return (
                  <div
                    key={booking.id}
                    data-testid={`ticket-${index}`}
                    className="ticket-card animate-fadeIn"
                    style={{ animationDelay: `${index * 0.1}s` }}
                  >
                    <div className="grid md:grid-cols-3 gap-6 relative z-10">
                      <div>
                        {event && (
                          <img
                            src={event.image_url}
                            alt={event.name}
                            className="w-full h-48 object-cover rounded-xl"
                          />
                        )}
                      </div>
                      <div className="md:col-span-2">
                        <div className="flex justify-between items-start mb-4">
                          <div>
                            <h3 className="text-2xl font-bold text-white mb-2">
                              {event?.name}
                            </h3>
                            <div className="flex items-center gap-2 text-gray-300 mb-2">
                              <Calendar className="w-4 h-4 text-indigo-400" />
                              <span>{event && new Date(event.date).toLocaleDateString()}</span>
                            </div>
                            <div className="flex items-center gap-2 text-gray-300">
                              <MapPin className="w-4 h-4 text-indigo-400" />
                              <span>{event?.venue}</span>
                            </div>
                          </div>
                          <div className={`px-4 py-2 rounded-full text-sm font-semibold ${
                            booking.status === 'confirmed' 
                              ? 'bg-green-500/20 text-green-400' 
                              : 'bg-yellow-500/20 text-yellow-400'
                          }`}>
                            {booking.status.toUpperCase()}
                          </div>
                        </div>
                        
                        <div className="grid grid-cols-3 gap-4 mb-4">
                          <div>
                            <div className="text-gray-400 text-sm">Booking ID</div>
                            <div className="text-white font-mono text-sm">{booking.id.slice(0, 8)}</div>
                          </div>
                          <div>
                            <div className="text-gray-400 text-sm">Seats</div>
                            <div className="text-white font-semibold">{booking.seats.length}</div>
                          </div>
                          <div>
                            <div className="text-gray-400 text-sm">Total Price</div>
                            <div className="text-white font-semibold">${booking.total_price.toFixed(2)}</div>
                          </div>
                        </div>

                        {booking.status === 'confirmed' && booking.qr_code && (
                          <div className="flex gap-4">
                            <div className="flex-1">
                              <img
                                src={booking.qr_code}
                                alt="QR Code"
                                className="w-32 h-32 bg-white p-2 rounded-lg"
                              />
                            </div>
                            <div className="flex-1 flex items-end">
                              <Button
                                data-testid={`download-ticket-btn-${index}`}
                                onClick={() => downloadTicket(booking)}
                                className="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white"
                              >
                                <Download className="w-4 h-4 mr-2" />
                                Download Ticket
                              </Button>
                            </div>
                          </div>
                        )}
                      </div>
                    </div>
                  </div>
                );
              })}
            </div>
          )}
        </div>
      </div>
    </div>
  );
};

export default MyTickets;