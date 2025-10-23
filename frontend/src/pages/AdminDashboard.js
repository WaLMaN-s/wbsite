import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { api } from "../App";
import { Button } from "@/components/ui/button";
import { ArrowLeft, Music, DollarSign, Ticket, Users } from "lucide-react";
import { toast } from "sonner";

const AdminDashboard = ({ user }) => {
  const [stats, setStats] = useState(null);
  const [bookings, setBookings] = useState([]);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    try {
      const [statsRes, bookingsRes] = await Promise.all([
        api.get("/admin/stats"),
        api.get("/admin/bookings")
      ]);
      setStats(statsRes.data);
      setBookings(bookingsRes.data);
    } catch (error) {
      toast.error("Failed to load admin data");
    } finally {
      setLoading(false);
    }
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
              data-testid="back-home-admin-btn"
              onClick={() => navigate("/")}
              variant="ghost"
              className="text-white hover:bg-white/10"
            >
              <ArrowLeft className="w-5 h-5 mr-2" />
              Back
            </Button>
            <div className="flex items-center gap-3">
              <Music className="w-8 h-8 text-indigo-400" />
              <h1 className="text-2xl font-bold text-white">Admin Dashboard</h1>
            </div>
          </div>
        </div>
      </nav>

      <div className="pt-32 pb-20 px-6">
        <div className="max-w-7xl mx-auto">
          {/* Stats Cards */}
          <div className="grid md:grid-cols-3 gap-6 mb-8">
            <div className="glass-card p-6 animate-fadeIn">
              <div className="flex items-center justify-between mb-4">
                <div className="text-gray-400">Total Bookings</div>
                <Ticket className="w-8 h-8 text-indigo-400" />
              </div>
              <div className="text-4xl font-bold text-white" data-testid="total-bookings-stat">
                {stats?.total_bookings || 0}
              </div>
            </div>
            
            <div className="glass-card p-6 animate-fadeIn" style={{ animationDelay: '0.1s' }}>
              <div className="flex items-center justify-between mb-4">
                <div className="text-gray-400">Confirmed Bookings</div>
                <Users className="w-8 h-8 text-green-400" />
              </div>
              <div className="text-4xl font-bold text-white" data-testid="confirmed-bookings-stat">
                {stats?.confirmed_bookings || 0}
              </div>
            </div>
            
            <div className="glass-card p-6 animate-fadeIn" style={{ animationDelay: '0.2s' }}>
              <div className="flex items-center justify-between mb-4">
                <div className="text-gray-400">Total Revenue</div>
                <DollarSign className="w-8 h-8 text-purple-400" />
              </div>
              <div className="text-4xl font-bold gradient-text" data-testid="total-revenue-stat">
                ${stats?.total_revenue?.toFixed(2) || '0.00'}
              </div>
            </div>
          </div>

          {/* Bookings Table */}
          <div className="glass-card p-6 animate-fadeIn">
            <h2 className="text-2xl font-bold text-white mb-6">All Bookings</h2>
            <div className="overflow-x-auto">
              <table className="w-full">
                <thead>
                  <tr className="border-b border-white/10">
                    <th className="text-left py-3 px-4 text-gray-400 font-semibold">Booking ID</th>
                    <th className="text-left py-3 px-4 text-gray-400 font-semibold">User ID</th>
                    <th className="text-left py-3 px-4 text-gray-400 font-semibold">Seats</th>
                    <th className="text-left py-3 px-4 text-gray-400 font-semibold">Total Price</th>
                    <th className="text-left py-3 px-4 text-gray-400 font-semibold">Status</th>
                    <th className="text-left py-3 px-4 text-gray-400 font-semibold">Date</th>
                  </tr>
                </thead>
                <tbody>
                  {bookings.map((booking, index) => (
                    <tr key={booking.id} className="border-b border-white/5 hover:bg-white/5" data-testid={`booking-row-${index}`}>
                      <td className="py-3 px-4 text-gray-300 font-mono text-sm">{booking.id.slice(0, 8)}</td>
                      <td className="py-3 px-4 text-gray-300 font-mono text-sm">{booking.user_id.slice(0, 8)}</td>
                      <td className="py-3 px-4 text-white font-semibold">{booking.seats.length}</td>
                      <td className="py-3 px-4 text-white font-semibold">${booking.total_price.toFixed(2)}</td>
                      <td className="py-3 px-4">
                        <span className={`px-3 py-1 rounded-full text-sm font-semibold ${
                          booking.status === 'confirmed' 
                            ? 'bg-green-500/20 text-green-400' 
                            : booking.status === 'pending'
                            ? 'bg-yellow-500/20 text-yellow-400'
                            : 'bg-red-500/20 text-red-400'
                        }`}>
                          {booking.status.toUpperCase()}
                        </span>
                      </td>
                      <td className="py-3 px-4 text-gray-300">
                        {new Date(booking.booking_date).toLocaleDateString()}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default AdminDashboard;