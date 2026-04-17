<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'full_name',
        'age',
        'birth_date',
        'gender',
        'address',
        'weight',
        'height',
        'phone',
        'complaint',
        'complaint_duration',
        'therapy_date',
        'therapy_time',
        'treatment_type',
        'price',
        'status',
        'therapist_notes',
        'admin_notes',
        'admin_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'therapy_date' => 'date',
        'therapy_time' => 'datetime:H:i',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public static function getStatusList(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONFIRMED => 'Dikonfirmasi',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];
    }

    public static function getTreatmentTypes(): array
    {
        return [
            'Terapi Tulang Belakang' => 150000,
            'Terapi Saraf Kejepit' => 200000,
            'Terapi HNP (Hernia Nucleus Pulposus)' => 250000,
            'Terapi Sciatica' => 200000,
            'Terapi Syaraf Tangan' => 180000,
            'Terapi Kaku Leher' => 150000,
            'Terapi Encok/Gout' => 180000,
            'Terapi Rematik' => 180000,
            'Terapi Pegal-Pegal' => 150000,
            'Terapi Keseleo/Terpeleset' => 150000,
            'Terapi Nyeri Lutut' => 180000,
            'Terapi Nyeri Bahu' => 180000,
            'Terapi Migrain/Sakit Kepala' => 150000,
            'Terapi Insomnia/Sulit Tidur' => 150000,
            'Terapi Gangguan Pencernaan' => 180000,
            'Terapi Gangguan Pernapasan' => 180000,
            'Paket Lengkap (Full Body)' => 350000,
        ];
    }

    public static function getPriceForTreatment(string $treatmentType): float
    {
        $treatments = self::getTreatmentTypes();
        return $treatments[$treatmentType] ?? 0;
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_CONFIRMED => 'bg-blue-100 text-blue-800',
            self::STATUS_COMPLETED => 'bg-green-100 text-green-800',
            self::STATUS_CANCELLED => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getFormattedPrice(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public static function generateBookingCode(): string
    {
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -6));
        return 'MTA-' . $date . '-' . $random;
    }
}
