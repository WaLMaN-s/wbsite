import { useState } from "react";
import { api } from "../App";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { X } from "lucide-react";
import { toast } from "sonner";

const AuthModal = ({ mode, onClose, onSuccess }) => {
  const [formData, setFormData] = useState({
    email: "",
    password: "",
    full_name: "",
    phone: ""
  });
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);

    try {
      const endpoint = mode === "login" ? "/auth/login" : "/auth/register";
      const response = await api.post(endpoint, formData);
      
      localStorage.setItem("token", response.data.token);
      onSuccess(response.data.user);
      toast.success(mode === "login" ? "Logged in successfully!" : "Account created successfully!");
    } catch (error) {
      toast.error(error.response?.data?.detail || "Authentication failed");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
      <div className="glass-card p-8 max-w-md w-full relative animate-fadeIn">
        <button
          onClick={onClose}
          className="absolute top-4 right-4 text-gray-400 hover:text-white transition"
        >
          <X className="w-6 h-6" />
        </button>

        <h2 className="text-3xl font-bold text-white mb-6">
          {mode === "login" ? "Welcome Back" : "Create Account"}
        </h2>

        <form onSubmit={handleSubmit} className="space-y-4">
          {mode === "register" && (
            <>
              <div>
                <Label htmlFor="full_name" className="text-gray-300">Full Name</Label>
                <Input
                  id="full_name"
                  data-testid="full-name-input"
                  type="text"
                  value={formData.full_name}
                  onChange={(e) => setFormData({ ...formData, full_name: e.target.value })}
                  required
                  className="mt-2 bg-white/5 border-white/10 text-white"
                />
              </div>
              <div>
                <Label htmlFor="phone" className="text-gray-300">Phone</Label>
                <Input
                  id="phone"
                  data-testid="phone-input"
                  type="tel"
                  value={formData.phone}
                  onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                  required
                  className="mt-2 bg-white/5 border-white/10 text-white"
                />
              </div>
            </>
          )}
          <div>
            <Label htmlFor="email" className="text-gray-300">Email</Label>
            <Input
              id="email"
              data-testid="email-input"
              type="email"
              value={formData.email}
              onChange={(e) => setFormData({ ...formData, email: e.target.value })}
              required
              className="mt-2 bg-white/5 border-white/10 text-white"
            />
          </div>
          <div>
            <Label htmlFor="password" className="text-gray-300">Password</Label>
            <Input
              id="password"
              data-testid="password-input"
              type="password"
              value={formData.password}
              onChange={(e) => setFormData({ ...formData, password: e.target.value })}
              required
              className="mt-2 bg-white/5 border-white/10 text-white"
            />
          </div>
          <Button
            type="submit"
            data-testid="auth-submit-btn"
            disabled={loading}
            className="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white py-6 text-lg"
          >
            {loading ? "Processing..." : mode === "login" ? "Login" : "Register"}
          </Button>
        </form>
      </div>
    </div>
  );
};

export default AuthModal;