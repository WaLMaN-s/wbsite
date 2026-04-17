<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function index()
    {
        $treatmentTypes = Reservation::getTreatmentTypes();
        return view('reservasi.index', compact('treatmentTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:150',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'address' => 'required|string|max:1000',
            'weight' => 'nullable|numeric|min:1|max:300',
            'height' => 'nullable|numeric|min:1|max:250',
            'phone' => 'required|string|max:20',
            'complaint' => 'required|string|max:1000',
            'complaint_duration' => 'nullable|string|max:255',
            'therapy_date' => 'required|date|after_or_equal:today',
            'therapy_time' => 'required',
            'treatment_type' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $price = Reservation::getPriceForTreatment($validated['treatment_type']);
            
            $reservation = Reservation::create([
                'booking_code' => Reservation::generateBookingCode(),
                'full_name' => $validated['full_name'],
                'age' => $validated['age'],
                'birth_date' => $validated['birth_date'],
                'gender' => $validated['gender'],
                'address' => $validated['address'],
                'weight' => $validated['weight'] ?? null,
                'height' => $validated['height'] ?? null,
                'phone' => $validated['phone'],
                'complaint' => $validated['complaint'],
                'complaint_duration' => $validated['complaint_duration'] ?? null,
                'therapy_date' => $validated['therapy_date'],
                'therapy_time' => $validated['therapy_time'],
                'treatment_type' => $validated['treatment_type'],
                'price' => $price,
                'status' => Reservation::STATUS_PENDING,
            ]);

            DB::commit();

            // Send WhatsApp notification (optional)
            // $this->sendWhatsAppNotification($reservation);

            return redirect()->route('reservasi.success', $reservation->booking_code)
                ->with('success', 'Reservasi berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat reservasi. Silakan coba lagi.'])
                ->withInput();
        }
    }

    public function success($bookingCode)
    {
        $reservation = Reservation::where('booking_code', $bookingCode)->firstOrFail();
        return view('reservasi.success', compact('reservation'));
    }

    private function sendWhatsAppNotification(Reservation $reservation)
    {
        // Implementasi notifikasi WhatsApp akan ditambahkan nanti
        // Menggunakan Fonnte API atau WhatsApp Business API
    }
}
