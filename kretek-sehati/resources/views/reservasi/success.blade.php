@extends('layouts.app')

@section('title', 'Reservasi Berhasil - Kretek Sehati Depok')

@section('content')
<section class="py-20 bg-gradient-to-br from-primary/10 via-white to-primaryLight/10 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12 text-center">
            <!-- Success Icon -->
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <h1 class="font-heading text-3xl md:text-4xl font-bold text-dark mb-4">Reservasi Berhasil!</h1>
            <p class="text-gray-600 mb-8">Terima kasih telah melakukan reservasi. Admin kami akan segera menghubungi Anda untuk konfirmasi.</p>

            <!-- Booking Details -->
            <div class="bg-gray-50 rounded-xl p-6 mb-8 text-left">
                <h2 class="font-heading font-semibold text-dark mb-4 text-lg">Detail Reservasi</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Kode Booking:</span>
                        <span class="font-semibold text-primary">{{ $reservation->booking_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama:</span>
                        <span class="font-medium">{{ $reservation->full_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Terapi:</span>
                        <span class="font-medium">{{ $reservation->therapy_date->format('d F Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jam Terapi:</span>
                        <span class="font-medium">{{ $reservation->therapy_time }} WIB</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jenis Treatment:</span>
                        <span class="font-medium">{{ $reservation->treatment_type }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-3 mt-3">
                        <span class="text-gray-600">Total Biaya:</span>
                        <span class="font-bold text-primary text-lg">{{ $reservation->getFormattedPrice() }}</span>
                    </div>
                </div>
            </div>

            <!-- Status Badge -->
            <div class="inline-block {{ $reservation->getStatusBadgeClass() }} px-4 py-2 rounded-full text-sm font-medium mb-6">
                Status: {{ ucfirst($reservation->status) }}
            </div>

            <!-- Next Steps -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8 text-left">
                <h3 class="font-heading font-semibold text-dark mb-3">Langkah Selanjutnya:</h3>
                <ol class="space-y-2 text-gray-700">
                    <li class="flex items-start">
                        <span class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">1</span>
                        <span>Admin kami akan menghubungi Anda melalui WhatsApp/telepon untuk konfirmasi jadwal</span>
                    </li>
                    <li class="flex items-start">
                        <span class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">2</span>
                        <span>Datang 15 menit sebelum jadwal terapi untuk registrasi ulang</span>
                    </li>
                    <li class="flex items-start">
                        <span class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs mr-3 mt-0.5">3</span>
                        <span>Bawa kode booking ini sebagai bukti reservasi</span>
                    </li>
                </ol>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="https://wa.me/6281234567890?text=Halo%20Kretek%20Sehati%2C%20saya%20ingin%20konfirmasi%20reservasi%20dengan%20kode%20{{ $reservation->booking_code }}" 
                   target="_blank"
                   class="inline-flex items-center justify-center bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-xl font-semibold transition">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.437-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                    Konfirmasi via WhatsApp
                </a>
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center justify-center bg-primary hover:bg-secondary text-white px-8 py-4 rounded-xl font-semibold transition">
                    Kembali ke Beranda
                </a>
            </div>

            <!-- Contact Info -->
            <div class="mt-8 pt-8 border-t text-gray-600 text-sm">
                <p>Butuh bantuan? Hubungi kami di</p>
                <p class="font-semibold text-primary mt-1">0812-3456-7890</p>
            </div>
        </div>
    </div>
</section>
@endsection
