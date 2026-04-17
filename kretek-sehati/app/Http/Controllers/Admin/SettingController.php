<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $businessHours = Setting::getBusinessHours();
        $contactInfo = Setting::getContactInfo();
        
        return view('admin.settings.index', compact('businessHours', 'contactInfo'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'google_maps_link' => 'nullable|url|max:500',
            'monday_open' => 'nullable|date_format:H:i',
            'monday_close' => 'nullable|date_format:H:i',
            'tuesday_open' => 'nullable|date_format:H:i',
            'tuesday_close' => 'nullable|date_format:H:i',
            'wednesday_open' => 'nullable|date_format:H:i',
            'wednesday_close' => 'nullable|date_format:H:i',
            'thursday_open' => 'nullable|date_format:H:i',
            'thursday_close' => 'nullable|date_format:H:i',
            'friday_open' => 'nullable|date_format:H:i',
            'friday_close' => 'nullable|date_format:H:i',
            'saturday_open' => 'nullable|date_format:H:i',
            'saturday_close' => 'nullable|date_format:H:i',
            'sunday_open' => 'nullable|date_format:H:i',
            'sunday_close' => 'nullable|date_format:H:i',
        ]);

        // Update contact info
        Setting::set('phone', $request->phone ?? '', 'text', 'Nomor Telepon');
        Setting::set('whatsapp', $request->whatsapp ?? '', 'text', 'Nomor WhatsApp');
        Setting::set('email', $request->email ?? '', 'text', 'Email');
        Setting::set('address', $request->address ?? '', 'text', 'Alamat');
        Setting::set('google_maps_link', $request->google_maps_link ?? '', 'text', 'Link Google Maps');

        // Update business hours
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach ($days as $day) {
            if (isset($validated["{$day}_open"])) {
                Setting::set("{$day}_open", $validated["{$day}_open"], 'text', ucfirst($day) . ' Open');
            }
            if (isset($validated["{$day}_close"])) {
                Setting::set("{$day}_close", $validated["{$day}_close"], 'text', ucfirst($day) . ' Close');
            }
        }

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
