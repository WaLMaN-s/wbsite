import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { api } from "../App";
import AuthModal from "../components/AuthModal";
import { Button } from "@/components/ui/button";
import { Calendar, MapPin, Ticket, User, Music } from "lucide-react";
import { toast } from "sonner";

const HomePage = ({ user, setUser }) => {
  const [events, setEvents] = useState([]);
  const [showAuth, setShowAuth] = useState(false);
  const [authMode, setAuthMode] = useState("login");
  const navigate = useNavigate();

  useEffect(() => {
    loadEvents();
    initializeData();
  }, []);

  const initializeData = async () => {
    try {
      await api.post("/init-data");
    } catch (error) {
      // Data already initialized
    }
  };

  const loadEvents = async () => {
    try {
      const response = await api.get("/events");
      setEvents(response.data);
    } catch (error) {
      toast.error("Failed to load events");
    }
  };

  const handleLogout = () => {
    localStorage.removeItem("token");
    setUser(null);
    toast.success("Logged out successfully");
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900">
      {/* Navigation */}
      <nav className="fixed top-0 left-0 right-0 z-50 bg-black/30 backdrop-blur-xl border-b border-white/10">
        <div className="max-w-7xl mx-auto px-6 py-4">
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-3">
              <Music className="w-8 h-8 text-indigo-400" />
              <h1 className="text-2xl font-bold text-white">FEAST TICKETS</h1>
            </div>
            <div className="flex items-center gap-4">
              {user ? (
                <>
                  <Button
                    data-testid="my-tickets-btn"
                    onClick={() => navigate("/my-tickets")}
                    variant="ghost"
                    className="text-white hover:bg-white/10"
                  >
                    <Ticket className="w-4 h-4 mr-2" />
                    My Tickets
                  </Button>
                  <Button
                    data-testid="admin-btn"
                    onClick={() => navigate("/admin")}
                    variant="ghost"
                    className="text-white hover:bg-white/10"
                  >
                    Admin
                  </Button>
                  <div className="flex items-center gap-2 text-white">
                    <User className="w-5 h-5" />
                    <span>{user.full_name}</span>
                  </div>
                  <Button
                    data-testid="logout-btn"
                    onClick={handleLogout}
                    variant="outline"
                    className="border-white/20 text-white hover:bg-white/10"
                  >
                    Logout
                  </Button>
                </>
              ) : (
                <>
                  <Button
                    data-testid="login-btn"
                    onClick={() => {
                      setAuthMode("login");
                      setShowAuth(true);
                    }}
                    variant="ghost"
                    className="text-white hover:bg-white/10"
                  >
                    Login
                  </Button>
                  <Button
                    data-testid="register-btn"
                    onClick={() => {
                      setAuthMode("register");
                      setShowAuth(true);
                    }}
                    className="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white"
                  >
                    Register
                  </Button>
                </>
              )}
            </div>
          </div>
        </div>
      </nav>

      {/* Hero Section */}
      <div className="pt-32 pb-20 px-6">
        <div className="max-w-7xl mx-auto">
          <div className="text-center mb-16 animate-fadeIn">
            <h2 className="text-6xl sm:text-7xl lg:text-8xl font-bold text-white mb-6">
              Experience
              <span className="block gradient-text">FEAST LIVE</span>
            </h2>
            <p className="text-xl text-gray-300 max-w-2xl mx-auto">
              Get your tickets now for the most anticipated concert of the year. Don't miss out on this spectacular show!
            </p>
          </div>

          {/* Events Grid */}
          <div className="grid gap-8 max-w-4xl mx-auto">
            {events.map((event, index) => (
              <div
                key={event.id}
                data-testid={`event-card-${index}`}
                className="glass-card hover-lift p-8 animate-fadeIn"
                style={{ animationDelay: `${index * 0.1}s` }}
              >
                <div className="grid md:grid-cols-2 gap-8">
                  <div className="relative overflow-hidden rounded-xl">
                    <img
                      src={event.image_url}
                      alt={event.name}
                      className="w-full h-full object-cover"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent" />
                  </div>
                  <div className="flex flex-col justify-between">
                    <div>
                      <h3 className="text-3xl font-bold text-white mb-4">{event.name}</h3>
                      <p className="text-gray-300 mb-6">{event.description}</p>
                      <div className="space-y-3">
                        <div className="flex items-center gap-3 text-gray-300">
                          <Calendar className="w-5 h-5 text-indigo-400" />
                          <span>{new Date(event.date).toLocaleDateString('en-US', { 
                            weekday: 'long', 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric' 
                          })}</span>
                        </div>
                        <div className="flex items-center gap-3 text-gray-300">
                          <MapPin className="w-5 h-5 text-indigo-400" />
                          <span>{event.venue}</span>
                        </div>
                      </div>
                    </div>
                    <Button
                      data-testid={`book-tickets-btn-${index}`}
                      onClick={() => navigate(`/event/${event.id}`)}
                      className="mt-6 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-lg py-6"
                    >
                      <Ticket className="w-5 h-5 mr-2" />
                      Book Tickets
                    </Button>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Auth Modal */}
      {showAuth && (
        <AuthModal
          mode={authMode}
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

export default HomePage;