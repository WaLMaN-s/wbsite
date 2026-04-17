@extends('layouts.app')

@section('title', 'Kretek Sehati Depok - Terapi Tulang dan Otot Profesional')

@section('content')
<!-- Hero Section -->
<section id="home" class="relative bg-gradient-to-br from-primary/10 via-white to-primaryLight/10 py-20 md:py-32">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="text-center md:text-left">
                <div class="inline-flex items-center space-x-2 bg-primary/10 text-primary px-4 py-2 rounded-full text-sm font-medium mb-6">
                    <span class="w-2 h-2 bg-primary rounded-full animate-pulse"></span>
                    <span>Buka Setiap Hari</span>
                </div>
                <h1 class="font-heading text-4xl md:text-5xl lg:text-6xl font-bold text-dark leading-tight mb-6">
                    Mending <span class="text-primary">Terapi Aja</span> di Kretek Sehati
                </h1>
                <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                    Solusi tepat untuk masalah tulang, otot, dan saraf Anda. Dengan terapis berpengalaman dan teknik terapi tradisional yang terbukti efektif.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                    <a href="{{ route('reservasi.index') }}" class="bg-primary hover:bg-secondary text-white px-8 py-4 rounded-xl font-semibold text-lg transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Booking Sekarang
                    </a>
                    <a href="#services" class="border-2 border-primary text-primary hover:bg-primary hover:text-white px-8 py-4 rounded-xl font-semibold text-lg transition">
                        Lihat Layanan
                    </a>
                </div>
            </div>
            <div class="relative">
                <div class="aspect-square max-w-md mx-auto">
                    <div class="absolute inset-0 bg-primary/20 rounded-full blur-3xl"></div>
                    <div class="relative bg-white rounded-3xl shadow-2xl p-8 transform rotate-3 hover:rotate-0 transition duration-500">
                        <div class="w-20 h-20 bg-black rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="text-white font-bold text-2xl">mta</span>
                        </div>
                        <h3 class="font-heading text-2xl font-bold text-center text-dark mb-2">Kretek Sehati Depok</h3>
                        <p class="text-primary text-center font-medium mb-6">Mending Terapi Aja</p>
                        <div class="space-y-3">
                            <div class="flex items-center justify-center space-x-2 text-gray-600">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Terapis Berpengalaman</span>
                            </div>
                            <div class="flex items-center justify-center space-x-2 text-gray-600">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Teknik Tradisional</span>
                            </div>
                            <div class="flex items-center justify-center space-x-2 text-gray-600">
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Harga Terjangkau</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mengapa Memilih Kami -->
<section id="about" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-dark mb-4">Mengapa Memilih Kami?</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Kami berkomitmen memberikan layanan terapi terbaik untuk kesehatan tulang dan otot Anda</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-gradient-to-br from-primary/5 to-primaryLight/5 rounded-2xl p-8 text-center hover:shadow-lg transition">
                <div class="w-16 h-16 bg-primary rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-heading text-xl font-semibold text-dark mb-3">Terapis Profesional</h3>
                <p class="text-gray-600">Tim terapis kami berpengalaman dan tersertifikasi dalam menangani berbagai keluhan tulang dan otot.</p>
            </div>
            
            <div class="bg-gradient-to-br from-primary/5 to-primaryLight/5 rounded-2xl p-8 text-center hover:shadow-lg transition">
                <div class="w-16 h-16 bg-primary rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <h3 class="font-heading text-xl font-semibold text-dark mb-3">Teknik Tradisional</h3>
                <p class="text-gray-600">Menggunakan metode terapi tradisional yang telah terbukti efektif selama generations.</p>
            </div>
            
            <div class="bg-gradient-to-br from-primary/5 to-primaryLight/5 rounded-2xl p-8 text-center hover:shadow-lg transition">
                <div class="w-16 h-16 bg-primary rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-heading text-xl font-semibold text-dark mb-3">Harga Terjangkau</h3>
                <p class="text-gray-600">Layanan berkualitas dengan harga yang ramah di kantong. Transparan tanpa biaya tersembunyi.</p>
            </div>
        </div>
    </div>
</section>

<!-- Layanan -->
<section id="services" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-dark mb-4">Layanan Kami</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Berbagai jenis terapi untuk mengatasi keluhan tulang, otot, dan saraf Anda</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $services = [
                ['name' => 'Terapi Tulang Belakang', 'icon' => '🦴'],
                ['name' => 'Terapi Saraf Kejepit', 'icon' => '⚡'],
                ['name' => 'Terapi HNP', 'icon' => '🔬'],
                ['name' => 'Terapi Sciatica', 'icon' => '🦵'],
                ['name' => 'Terapi Syaraf Tangan', 'icon' => '✋'],
                ['name' => 'Terapi Kaku Leher', 'icon' => '颈部'],
                ['name' => 'Terapi Encok/Gout', 'icon' => '🦶'],
                ['name' => 'Terapi Rematik', 'icon' => '🌡️'],
                ['name' => 'Terapi Pegal-Pegal', 'icon' => '💆'],
                ['name' => 'Terapi Keseleo', 'icon' => '🔄'],
                ['name' => 'Terapi Nyeri Lutut', 'icon' => '🦵'],
                ['name' => 'Paket Full Body', 'icon' => '✨'],
            ];
            @endphp
            
            @foreach($services as $service)
            <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl transition flex items-center space-x-4">
                <span class="text-4xl">{{ $service['icon'] }}</span>
                <h3 class="font-heading font-semibold text-dark">{{ $service['name'] }}</h3>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Tarif -->
<section id="pricing" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-dark mb-4">Tarif Terapi</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Harga transparan dan terjangkau untuk setiap jenis treatment</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @php
            $treatments = [
                ['name' => 'Terapi Tulang Belakang', 'price' => 'Rp 150.000'],
                ['name' => 'Terapi Saraf Kejepit', 'price' => 'Rp 200.000'],
                ['name' => 'Terapi HNP (Hernia Nucleus Pulposus)', 'price' => 'Rp 250.000'],
                ['name' => 'Terapi Sciatica', 'price' => 'Rp 200.000'],
                ['name' => 'Terapi Syaraf Tangan', 'price' => 'Rp 180.000'],
                ['name' => 'Terapi Kaku Leher', 'price' => 'Rp 150.000'],
                ['name' => 'Terapi Encok/Gout', 'price' => 'Rp 180.000'],
                ['name' => 'Terapi Rematik', 'price' => 'Rp 180.000'],
                ['name' => 'Terapi Pegal-Pegal', 'price' => 'Rp 150.000'],
                ['name' => 'Terapi Keseleo/Terpeleset', 'price' => 'Rp 150.000'],
                ['name' => 'Terapi Nyeri Lutut', 'price' => 'Rp 180.000'],
                ['name' => 'Terapi Nyeri Bahu', 'price' => 'Rp 180.000'],
                ['name' => 'Terapi Migrain/Sakit Kepala', 'price' => 'Rp 150.000'],
                ['name' => 'Terapi Insomnia/Sulit Tidur', 'price' => 'Rp 150.000'],
                ['name' => 'Terapi Gangguan Pencernaan', 'price' => 'Rp 180.000'],
                ['name' => 'Terapi Gangguan Pernapasan', 'price' => 'Rp 180.000'],
                ['name' => 'Paket Lengkap (Full Body)', 'price' => 'Rp 350.000', 'featured' => true],
            ];
            @endphp
            
            @foreach($treatments as $treatment)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition {{ isset($treatment['featured']) ? 'ring-2 ring-primary transform scale-105' : '' }}">
                <div class="p-6">
                    <h3 class="font-heading font-semibold text-dark text-lg mb-3">{{ $treatment['name'] }}</h3>
                    <div class="flex items-baseline">
                        <span class="text-3xl font-bold text-primary">{{ $treatment['price'] }}</span>
                        <span class="text-gray-500 ml-2">/ sesi</span>
                    </div>
                </div>
                @if(isset($treatment['featured']))
                <div class="bg-primary text-white text-center py-2 text-sm font-medium">
                    ⭐ Paling Laris
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Cara Reservasi -->
<section class="py-20 bg-gradient-to-br from-primary/10 via-white to-primaryLight/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-dark mb-4">Cara Reservasi</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Mudah dan cepat, hanya dalam 3 langkah sederhana</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-primary text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6">1</div>
                <h3 class="font-heading text-xl font-semibold text-dark mb-3">Isi Form Reservasi</h3>
                <p class="text-gray-600">Lengkapi formulir reservasi online dengan data diri dan keluhan Anda.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-primary text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6">2</div>
                <h3 class="font-heading text-xl font-semibold text-dark mb-3">Konfirmasi Admin</h3>
                <p class="text-gray-600">Admin kami akan menghubungi Anda untuk konfirmasi jadwal reservasi.</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-primary text-white rounded-full flex items-center justify-center text-2xl font-bold mx-auto mb-6">3</div>
                <h3 class="font-heading text-xl font-semibold text-dark mb-3">Datang untuk Terapi</h3>
                <p class="text-gray-600">Datang sesuai jadwal yang telah ditentukan dan dapatkan terapi Anda.</p>
            </div>
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ route('reservasi.index') }}" class="inline-block bg-primary hover:bg-secondary text-white px-10 py-4 rounded-xl font-semibold text-lg transition shadow-lg hover:shadow-xl">
                Reservasi Sekarang
            </a>
        </div>
    </div>
</section>

<!-- Testimoni -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="font-heading text-3xl md:text-4xl font-bold text-dark mb-4">Apa Kata Mereka?</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Testimoni dari pelanggan yang telah merasakan manfaat terapi kami</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
            $testimonials = [
                ['name' => 'Budi Santoso', 'text' => 'Sakit punggung saya sudah bertahun-tahun, setelah terapi di sini rasanya jauh lebih baik. Terapisnya sangat profesional!', 'rating' => 5],
                ['name' => 'Siti Nurhaliza', 'text' => 'Awalnya ragu, tapi setelah coba sekali langsung ketagihan. Sakit leher saya yang kronis akhirnya membaik.', 'rating' => 5],
                ['name' => 'Ahmad Wijaya', 'text' => 'Tempat nyaman, terapis ramah dan ahli. Harga juga sangat terjangkau untuk kualitas seperti ini.', 'rating' => 5],
            ];
            @endphp
            
            @foreach($testimonials as $testimonial)
            <div class="bg-gray-50 rounded-2xl p-8">
                <div class="flex mb-4">
                    @for($i = 0; $i < $testimonial['rating']; $i++)
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>
                <p class="text-gray-600 mb-6 italic">"{{ $testimonial['text'] }}"</p>
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold">
                        {{ substr($testimonial['name'], 0, 1) }}
                    </div>
                    <div class="ml-3">
                        <p class="font-semibold text-dark">{{ $testimonial['name'] }}</p>
                        <p class="text-sm text-gray-500">Pelanggan Setia</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Lokasi & Jam Operasional -->
<section id="location" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Google Maps -->
            <div>
                <h2 class="font-heading text-3xl font-bold text-dark mb-6">Lokasi Kami</h2>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126920.26149919596!2d106.7972829!3d-6.4024991!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69e7c2b0b0b0b1%3A0x123456789abcdef!2sDepok%2C%20Jawa%20Barat!5e0!3m2!1sid!2sid!4v1234567890" 
                        width="100%" 
                        height="400" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <div class="mt-6 space-y-3">
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-gray-700">Depok, Jawa Barat</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="text-gray-700">0812-3456-7890</span>
                    </div>
                </div>
            </div>
            
            <!-- Jam Operasional -->
            <div>
                <h2 class="font-heading text-3xl font-bold text-dark mb-6">Jam Operasional</h2>
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span class="font-medium text-dark">Senin - Jumat</span>
                            <span class="text-primary font-semibold">08:00 - 20:00 WIB</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span class="font-medium text-dark">Sabtu</span>
                            <span class="text-primary font-semibold">08:00 - 20:00 WIB</span>
                        </div>
                        <div class="flex justify-between items-center pb-3 border-b">
                            <span class="font-medium text-dark">Minggu</span>
                            <span class="text-primary font-semibold">09:00 - 18:00 WIB</span>
                        </div>
                    </div>
                    
                    <div class="mt-8 bg-primary/10 rounded-xl p-6">
                        <h3 class="font-heading font-semibold text-dark mb-3">📞 Butuh Konsultasi?</h3>
                        <p class="text-gray-600 mb-4">Hubungi kami untuk konsultasi gratis mengenai keluhan Anda</p>
                        <a href="https://wa.me/6281234567890" target="_blank" class="inline-flex items-center space-x-2 bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-medium transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.437-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            <span>Chat WhatsApp</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-heading text-3xl md:text-4xl font-bold text-white mb-6">Siap Merasakan Perubahannya?</h2>
        <p class="text-white/90 text-lg mb-8 max-w-2xl mx-auto">Jangan tunda lagi kesehatan Anda. Booking sekarang dan rasakan manfaat terapi tulang dan otot profesional.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('reservasi.index') }}" class="bg-white text-primary hover:bg-gray-100 px-10 py-4 rounded-xl font-semibold text-lg transition shadow-lg">
                Booking Online
            </a>
            <a href="tel:+6281234567890" class="border-2 border-white text-white hover:bg-white hover:text-primary px-10 py-4 rounded-xl font-semibold text-lg transition">
                Hubungi Kami
            </a>
        </div>
    </div>
</section>
@endsection
