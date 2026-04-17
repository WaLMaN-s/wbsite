<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value, $type = 'text', $description = null)
    {
        return self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description,
            ]
        );
    }

    public static function getBusinessHours(): array
    {
        return [
            'monday_open' => self::get('monday_open', '08:00'),
            'monday_close' => self::get('monday_close', '20:00'),
            'tuesday_open' => self::get('tuesday_open', '08:00'),
            'tuesday_close' => self::get('tuesday_close', '20:00'),
            'wednesday_open' => self::get('wednesday_open', '08:00'),
            'wednesday_close' => self::get('wednesday_close', '20:00'),
            'thursday_open' => self::get('thursday_open', '08:00'),
            'thursday_close' => self::get('thursday_close', '20:00'),
            'friday_open' => self::get('friday_open', '08:00'),
            'friday_close' => self::get('friday_close', '20:00'),
            'saturday_open' => self::get('saturday_open', '08:00'),
            'saturday_close' => self::get('saturday_close', '20:00'),
            'sunday_open' => self::get('sunday_open', '09:00'),
            'sunday_close' => self::get('sunday_close', '18:00'),
        ];
    }

    public static function getContactInfo(): array
    {
        return [
            'phone' => self::get('phone', '081234567890'),
            'whatsapp' => self::get('whatsapp', '6281234567890'),
            'email' => self::get('email', 'info@kreteksehati.com'),
            'address' => self::get('address', 'Depok, Jawa Barat'),
            'google_maps_link' => self::get('google_maps_link', 'https://maps.google.com'),
        ];
    }
}
