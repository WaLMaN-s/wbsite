import { useState, useEffect } from "react";
import { useNavigate, useSearchParams } from "react-router-dom";
import { api } from "../App";
import { Button } from "@/components/ui/button";
import { CheckCircle, Music } from "lucide-react";
import { toast } from "sonner";

const PaymentSuccess = ({ user }) => {
  const [searchParams] = useSearchParams();
  const sessionId = searchParams.get("session_id");
  const [checking, setChecking] = useState(true);
  const [paymentStatus, setPaymentStatus] = useState(null);
  const navigate = useNavigate();

  useEffect(() => {
    if (sessionId) {
      pollPaymentStatus();
    }
  }, [sessionId]);

  const pollPaymentStatus = async (attempts = 0) => {
    const maxAttempts = 5;
    
    if (attempts >= maxAttempts) {
      setChecking(false);
      toast.error("Payment verification timed out. Please check your tickets.");
      return;
    }

    try {
      const response = await api.get(`/payment/status/${sessionId}`);
      setPaymentStatus(response.data);
      
      if (response.data.payment_status === "paid") {
        setChecking(false);
        toast.success("Payment successful!");
        return;
      } else if (response.data.status === "expired") {
        setChecking(false);
        toast.error("Payment session expired");
        return;
      }

      // Continue polling
      setTimeout(() => pollPaymentStatus(attempts + 1), 2000);
    } catch (error) {
      console.error("Error checking payment status:", error);
      setChecking(false);
      toast.error("Failed to verify payment status");
    }
  };

  if (checking) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900">
        <div className="glass-card p-12 text-center">
          <div className="spinner mx-auto mb-6" />
          <h2 className="text-2xl font-bold text-white mb-2">Processing Payment</h2>
          <p className="text-gray-300">Please wait while we confirm your payment...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900">
      {/* Navigation */}
      <nav className="fixed top-0 left-0 right-0 z-50 bg-black/30 backdrop-blur-xl border-b border-white/10">
        <div className="max-w-7xl mx-auto px-6 py-4">
          <div className="flex items-center gap-3">
            <Music className="w-8 h-8 text-indigo-400" />
            <h1 className="text-2xl font-bold text-white">FEAST TICKETS</h1>
          </div>
        </div>
      </nav>

      <div className="pt-32 pb-20 px-6">
        <div className="max-w-2xl mx-auto">
          <div className="glass-card p-12 text-center animate-fadeIn">
            {paymentStatus?.payment_status === "paid" ? (
              <>
                <div className="mb-6">
                  <CheckCircle className="w-24 h-24 text-green-400 mx-auto" />
                </div>
                <h2 className="text-4xl font-bold text-white mb-4">Payment Successful!</h2>
                <p className="text-gray-300 text-lg mb-8">
                  Your tickets have been confirmed. Check your email for the confirmation and ticket details.
                </p>
                <div className="space-y-4">
                  <Button
                    data-testid="view-tickets-btn"
                    onClick={() => navigate("/my-tickets")}
                    className="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-lg py-6"
                  >
                    View My Tickets
                  </Button>
                  <Button
                    data-testid="back-home-success-btn"
                    onClick={() => navigate("/")}
                    variant="outline"
                    className="w-full border-white/20 text-white hover:bg-white/10"
                  >
                    Back to Home
                  </Button>
                </div>
              </>
            ) : (
              <>
                <h2 className="text-3xl font-bold text-white mb-4">Payment Processing</h2>
                <p className="text-gray-300 mb-8">
                  Your payment is being processed. Please check your tickets page shortly.
                </p>
                <Button
                  onClick={() => navigate("/my-tickets")}
                  className="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white"
                >
                  View My Tickets
                </Button>
              </>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default PaymentSuccess;