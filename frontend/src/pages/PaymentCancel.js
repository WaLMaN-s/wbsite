import { useNavigate } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { XCircle, Music } from "lucide-react";

const PaymentCancel = () => {
  const navigate = useNavigate();

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
            <div className="mb-6">
              <XCircle className="w-24 h-24 text-red-400 mx-auto" />
            </div>
            <h2 className="text-4xl font-bold text-white mb-4">Payment Cancelled</h2>
            <p className="text-gray-300 text-lg mb-8">
              Your payment was cancelled. Your seat reservations have been released.
            </p>
            <div className="space-y-4">
              <Button
                data-testid="try-again-btn"
                onClick={() => navigate(-2)}
                className="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-lg py-6"
              >
                Try Again
              </Button>
              <Button
                data-testid="back-home-cancel-btn"
                onClick={() => navigate("/")}
                variant="outline"
                className="w-full border-white/20 text-white hover:bg-white/10"
              >
                Back to Home
              </Button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default PaymentCancel;