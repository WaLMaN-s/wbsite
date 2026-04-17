@extends('layouts.app')

@section('title', 'Reservasi Terapi - Kretek Sehati Depok')

@section('content')
<section class="py-12 bg-gradient-to-br from-primary/10 via-white to-primaryLight/10 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="font-heading text-3xl md:text-4xl font-bold text-dark mb-4">Formulir Reservasi</h1>
            <p class="text-gray-600">Lengkapi data di bawah ini untuk melakukan reservasi terapi Anda</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-10">
            @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('reservasi.store') }}" method="POST" id="reservationForm">
                @csrf
                
                <!-- Data Diri -->
                <div class="mb-8">
                    <h2 class="font-heading text-xl font-semibold text-dark mb-4 flex items-center">
                        <span class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm mr-3">1</span>
                        Data Diri
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                placeholder="Masukkan nama lengkap">
                        </div>

                        <div>
                            <label for="age" class="block text-sm font-medium text-gray-700 mb-2">Usia <span class="text-red-500">*</span></label>
                            <input type="number" name="age" id="age" value="{{ old('age') }}" required min="1" max="150"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                placeholder="Contoh: 35">
                        </div>

                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select name="gender" id="gender" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Alamat <span class="text-red-500">*</span></label>
                            <textarea name="address" id="address" rows="3" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                        </div>

                        <div>
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">Berat Badan (kg)</label>
                            <input type="number" name="weight" id="weight" value="{{ old('weight') }}" step="0.01" min="1" max="300"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                placeholder="Contoh: 65">
                        </div>

                        <div>
                            <label for="height" class="block text-sm font-medium text-gray-700 mb-2">Tinggi Badan (cm)</label>
                            <input type="number" name="height" id="height" value="{{ old('height') }}" step="0.01" min="1" max="250"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                placeholder="Contoh: 170">
                        </div>

                        <div class="md:col-span-2">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon/WA <span class="text-red-500">*</span></label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                placeholder="Contoh: 0812-3456-7890">
                        </div>
                    </div>
                </div>

                <!-- Informasi Keluhan -->
                <div class="mb-8">
                    <h2 class="font-heading text-xl font-semibold text-dark mb-4 flex items-center">
                        <span class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm mr-3">2</span>
                        Informasi Keluhan
                    </h2>
                    
                    <div class="space-y-6">
                        <div>
                            <label for="complaint" class="block text-sm font-medium text-gray-700 mb-2">Keluhan Utama <span class="text-red-500">*</span></label>
                            <textarea name="complaint" id="complaint" rows="4" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                placeholder="Jelaskan keluhan Anda secara detail (misal: sakit punggung bagian bawah, sulit membungkuk, dll)">{{ old('complaint') }}</textarea>
                        </div>

                        <div>
                            <label for="complaint_duration" class="block text-sm font-medium text-gray-700 mb-2">Lama Keluhan</label>
                            <input type="text" name="complaint_duration" id="complaint_duration" value="{{ old('complaint_duration') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition"
                                placeholder="Contoh: 2 minggu, 3 bulan, 1 tahun">
                        </div>
                    </div>
                </div>

                <!-- Jadwal & Treatment -->
                <div class="mb-8">
                    <h2 class="font-heading text-xl font-semibold text-dark mb-4 flex items-center">
                        <span class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center text-sm mr-3">3</span>
                        Jadwal & Jenis Terapi
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="therapy_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Terapi <span class="text-red-500">*</span></label>
                            <input type="date" name="therapy_date" id="therapy_date" value="{{ old('therapy_date', date('Y-m-d')) }}" required min="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
                        </div>

                        <div>
                            <label for="therapy_time" class="block text-sm font-medium text-gray-700 mb-2">Jam Terapi <span class="text-red-500">*</span></label>
                            <select name="therapy_time" id="therapy_time" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
                                <option value="">Pilih Jam Terapi</option>
                                @php
                                $timeSlots = [
                                    '08:00', '09:00', '10:00', '11:00',
                                    '13:00', '14:00', '15:00', '16:00',
                                    '17:00', '18:00', '19:00'
                                ];
                                @endphp
                                @foreach($timeSlots as $time)
                                <option value="{{ $time }}" {{ old('therapy_time') == $time ? 'selected' : '' }}>{{ $time }} WIB</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label for="treatment_type" class="block text-sm font-medium text-gray-700 mb-2">Jenis Treatment <span class="text-red-500">*</span></label>
                            <select name="treatment_type" id="treatment_type" required onchange="updatePrice()"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition">
                                <option value="">Pilih Jenis Treatment</option>
                                @foreach($treatmentTypes as $name => $price)
                                <option value="{{ $name }}" data-price="{{ $price }}" {{ old('treatment_type') == $name ? 'selected' : '' }}>
                                    {{ $name }} - Rp {{ number_format($price, 0, ',', '.') }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Biaya -->
                <div id="priceSummary" class="hidden mb-8 bg-primary/5 rounded-xl p-6">
                    <h3 class="font-heading font-semibold text-dark mb-4">Ringkasan Biaya</h3>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Biaya Terapi:</span>
                        <span id="totalPrice" class="text-2xl font-bold text-primary">Rp 0</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">*Pembayaran dilakukan setelah sesi terapi selesai</p>
                </div>

                <!-- Submit Button -->
                <div class="flex flex-col md:flex-row gap-4">
                    <button type="submit"
                        class="flex-1 bg-primary hover:bg-secondary text-white px-8 py-4 rounded-xl font-semibold text-lg transition shadow-lg hover:shadow-xl">
                        <svg class="inline-block w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Kirim Reservasi
                    </button>
                    <a href="{{ route('home') }}"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-4 rounded-xl font-semibold text-lg transition text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>

@push('scripts')
<script>
function updatePrice() {
    const select = document.getElementById('treatment_type');
    const selectedOption = select.options[select.selectedIndex];
    const price = selectedOption.getAttribute('data-price');
    const summaryDiv = document.getElementById('priceSummary');
    const totalPriceSpan = document.getElementById('totalPrice');
    
    if (price && price > 0) {
        summaryDiv.classList.remove('hidden');
        const formattedPrice = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(price);
        totalPriceSpan.textContent = formattedPrice;
    } else {
        summaryDiv.classList.add('hidden');
    }
}

// Set minimum date to today
const today = new Date().toISOString().split('T')[0];
document.getElementById('therapy_date').setAttribute('min', today);
</script>
@endpush
@endsection
