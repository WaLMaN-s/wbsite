<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Admin;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $today = now()->format('Y-m-d');
        $thisMonth = now()->format('Y-m');

        $stats = [
            'total_today' => Reservation::whereDate('therapy_date', $today)->count(),
            'total_pending' => Reservation::where('status', Reservation::STATUS_PENDING)->count(),
            'total_confirmed' => Reservation::where('status', Reservation::STATUS_CONFIRMED)->count(),
            'total_this_month' => Reservation::whereYear('therapy_date', now()->year)
                ->whereMonth('therapy_date', now()->month)
                ->count(),
            'total_completed' => Reservation::where('status', Reservation::STATUS_COMPLETED)->count(),
            'total_cancelled' => Reservation::where('status', Reservation::STATUS_CANCELLED)->count(),
        ];

        $recentReservations = Reservation::with('admin')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Data untuk grafik (7 hari terakhir)
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartData[] = [
                'date' => $date->format('d M'),
                'count' => Reservation::whereDate('therapy_date', $date)->count(),
            ];
        }

        return view('admin.dashboard', compact('stats', 'recentReservations', 'chartData'));
    }
}
