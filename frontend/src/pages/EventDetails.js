import { useState, useEffect } from "react";
import { useNavigate, useParams } from "react-router-dom";
import { api } from "../App";
import AuthModal from "../components/AuthModal";
import { Button } from "@/components/ui/button";
import { ArrowLeft, Calendar, MapPin, Music, CheckCircle } from "lucide-react";
import { toast } from "sonner";

const EventDetails = ({ user, setUser }) => {
  const { eventId } = useParams();
  const [event, setEvent] = useState(null);
  const [tickets, setTickets] = useState([]);
  const [selectedCategory, setSelectedCategory] = useState(null);
  const [showAuth, setShowAuth] = useState(false);
  const navigate = useNavigate();

  useEffect(() => {
    loadEventDetails();
    loadTickets();
  }, [eventId]);

  const loadEventDetails = async () => {
    try {
      const response = await api.get(`/events/${eventId}`);
      setEvent(response.data);
    } catch (error) {
      toast.error("Failed to load event details");
    }
  };

  const loadTickets = async () => {
    try {
      const response = await api.get(`/events/${eventId}/tickets`);
      setTickets(response.data);
    } catch (error) {
      toast.error("Failed to load tickets");
    }
  };

  const handleSelectTicket = (category) => {
    if (!user) {
      setShowAuth(true);
      return;
    }
    setSelectedCategory(category);
    navigate(`/event/${eventId}/seats?category=${category}`);
  };

  if (!event) {
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
              data-testid="back-btn"
              onClick={() => navigate("/")}
              variant="ghost"
              className="text-white hover:bg-white/10"
            >
              <ArrowLeft className="w-5 h-5 mr-2" />
              Back
            </Button>
            <div className="flex items-center gap-3">
              <Music className="w-8 h-8 text-indigo-400" />
              <h1 className="text-2xl font-bold text-white">FEAST TICKETS</h1>
            </div>
          </div>
        </div>
      </nav>

      <div className="pt-32 pb-20 px-6">
        <div className="max-w-6xl mx-auto">
          {/* Event Header */}
          <div className="glass-card p-8 mb-8 animate-fadeIn">
            <div className="grid md:grid-cols-2 gap-8">
              <div className="relative overflow-hidden rounded-xl">
                <img
                  src={event.image_url}
                  alt={event.name}
                  className="w-full h-full object-cover"
                />
              </div>
              <div>
                <h2 className="text-4xl font-bold text-white mb-4">{event.name}</h2>
                <p className="text-gray-300 mb-6 text-lg">{event.description}</p>
                <div className="space-y-3">
                  <div className="flex items-center gap-3 text-gray-300">
                    <Calendar className="w-5 h-5 text-indigo-400" />
                    <span>{new Date(event.date).toLocaleDateString('en-US', { 
                      weekday: 'long', 
                      year: 'numeric', 
                      month: 'long', 
                      day: 'numeric',
                      hour: '2-digit',
                      minute: '2-digit'
                    })}</span>
                  </div>
                  <div className="flex items-center gap-3 text-gray-300">
                    <MapPin className="w-5 h-5 text-indigo-400" />
                    <span>{event.venue}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          {/* Ticket Categories */}
          <div className="mb-8">
            <h3 className="text-3xl font-bold text-white mb-6">Select Ticket Type</h3>
            <div className="grid md:grid-cols-3 gap-6">
              {tickets.map((ticket, index) => (
                <div
                  key={ticket.id}
                  data-testid={`ticket-category-${ticket.category.toLowerCase()}`}
                  className={`glass-card p-6 hover-lift card-hover cursor-pointer ${
                    ticket.available_seats === 0 ? 'opacity-50' : ''
                  }`}
                  style={{ animationDelay: `${index * 0.1}s` }}
                >
                  <div className="mb-4">
                    <h4 className="text-2xl font-bold text-white mb-2">{ticket.category}</h4>
                    <div className="text-3xl font-bold gradient-text">${ticket.price.toFixed(2)}</div>
                  </div>
                  <div className="space-y-2 mb-6">
                    {ticket.benefits.map((benefit, idx) => (
                      <div key={idx} className="flex items-start gap-2 text-gray-300">
                        <CheckCircle className="w-4 h-4 text-green-400 mt-1 flex-shrink-0" />
                        <span className="text-sm">{benefit}</span>
                      </div>
                    ))}
                  </div>
                  <div className="text-sm text-gray-400 mb-4">
                    {ticket.available_seats} / {ticket.total_seats} seats available
                  </div>
                  <Button
                    data-testid={`select-${ticket.category.toLowerCase()}-btn`}
                    onClick={() => handleSelectTicket(ticket.category)}
                    disabled={ticket.available_seats === 0}
                    className="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white"
                  >
                    {ticket.available_seats === 0 ? 'Sold Out' : 'Select Seats'}
                  </Button>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>

      {/* Auth Modal */}
      {showAuth && (
        <AuthModal
          mode="login"
          onClose={() => setShowAuth(false)}
          onSuccess={(userData) => {
            setUser(userData);
            setShowAuth(false);
          }}
        />
      )}
    </div>
  );
};

export default EventDetails;