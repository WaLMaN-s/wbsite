<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request)
    {
        $query = Reservation::query();

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('therapy_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('therapy_date', '<=', $request->date_to);
        }

        // Filter berdasarkan nama
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%')
                  ->orWhere('booking_code', 'like', '%' . $request->search . '%');
            });
        }

        $reservations = $query->orderBy('therapy_date', 'desc')
            ->orderBy('therapy_time', 'desc')
            ->paginate(15);

        $statusList = Reservation::getStatusList();

        return view('admin.reservations.index', compact('reservations', 'statusList'));
    }

    public function show($id)
    {
        $reservation = Reservation::with('admin')->findOrFail($id);
        return view('admin.reservations.show', compact('reservation'));
    }

    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        $statusList = Reservation::getStatusList();
        $treatmentTypes = Reservation::getTreatmentTypes();
        
        return view('admin.reservations.edit', compact('reservation', 'statusList', 'treatmentTypes'));
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'therapist_notes' => 'nullable|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $reservation->update([
            'status' => $validated['status'],
            'therapist_notes' => $validated['therapist_notes'] ?? null,
            'admin_notes' => $validated['admin_notes'] ?? null,
            'admin_id' => auth()->guard('admin')->id(),
        ]);

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Data reservasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reservasi berhasil dihapus.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $reservation = Reservation::findOrFail($id);
        $reservation->update([
            'status' => $request->status,
            'admin_id' => auth()->guard('admin')->id(),
        ]);

        return back()->with('success', 'Status berhasil diperbarui.');
    }
}
