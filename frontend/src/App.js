import { useState, useEffect } from "react";
import "@/App.css";
import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import axios from "axios";
import HomePage from "./pages/HomePage";
import EventDetails from "./pages/EventDetails";
import SeatSelection from "./pages/SeatSelection";
import MyTickets from "./pages/MyTickets";
import PaymentSuccess from "./pages/PaymentSuccess";
import PaymentCancel from "./pages/PaymentCancel";
import AdminDashboard from "./pages/AdminDashboard";
import { Toaster } from "@/components/ui/sonner";

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;
const API = `${BACKEND_URL}/api`;

export const api = axios.create({
  baseURL: API,
});

// Add auth token to requests
api.interceptors.request.use((config) => {
  const token = localStorage.getItem("token");
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

function App() {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const token = localStorage.getItem("token");
    if (token) {
      api.get("/auth/me")
        .then(response => {
          setUser(response.data);
        })
        .catch(() => {
          localStorage.removeItem("token");
        })
        .finally(() => {
          setLoading(false);
        });
    } else {
      setLoading(false);
    }
  }, []);

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
        <div className="text-white text-xl">Loading...</div>
      </div>
    );
  }

  return (
    <div className="App">
      <Toaster position="top-center" richColors />
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<HomePage user={user} setUser={setUser} />} />
          <Route path="/event/:eventId" element={<EventDetails user={user} setUser={setUser} />} />
          <Route 
            path="/event/:eventId/seats" 
            element={user ? <SeatSelection user={user} /> : <Navigate to="/" />} 
          />
          <Route 
            path="/my-tickets" 
            element={user ? <MyTickets user={user} /> : <Navigate to="/" />} 
          />
          <Route 
            path="/admin" 
            element={user ? <AdminDashboard user={user} /> : <Navigate to="/" />} 
          />
          <Route path="/payment/success" element={<PaymentSuccess user={user} />} />
          <Route path="/payment/cancel" element={<PaymentCancel />} />
        </Routes>
      </BrowserRouter>
    </div>
  );
}

export default App;