# Kretek Sehati Depok - Sistem Reservasi Online Terapi Tulang dan Otot

**Rancang Bangun Aplikasi Web Sistem Reservasi Online Pendaftaran Terapi Tulang dan Otot pada Kretek Sehati Depok Berbasis Laravel**

## 🚀 Fitur Utama

### Frontend (Customer Side)
- ✅ Halaman Home yang menarik, calming, dan profesional
- ✅ Section Hero, Mengapa Memilih Kami, Layanan, Cara Reservasi, Tarif Terapi
- ✅ Testimoni, Lokasi + Google Maps, Jam Operasional
- ✅ Form Reservasi Lengkap dengan validasi
- ✅ Ringkasan biaya otomatis
- ✅ Konfirmasi reservasi via WhatsApp

### Backend Admin Panel
- ✅ Sistem autentikasi terpisah untuk admin
- ✅ Dashboard dengan statistik real-time
- ✅ Grafik reservasi 7 hari terakhir (Chart.js)
- ✅ Manajemen reservasi lengkap (CRUD)
- ✅ Filter berdasarkan status, tanggal, dan pencarian
- ✅ Update status reservasi (Pending, Dikonfirmasi, Selesai, Dibatalkan)
- ✅ Catatan terapis dan admin
- ✅ Pengaturan jam operasional dan kontak

## 🛠️ Teknologi

- **Framework:** Laravel 11
- **Authentication:** Laravel Breeze (Custom Admin Guard)
- **Frontend:** Blade Template + Tailwind CSS 3
- **Database:** SQLite/MySQL
- **Charts:** Chart.js
- **Font:** Inter & Poppins
- **Warna Utama:** Hijau Toska (#0EA5A5)

## 📁 Struktur Folder Project

```
kretek-sehati/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/
│   │       │   ├── AuthController.php
│   │       │   ├── DashboardController.php
│   │       │   ├── ReservationAdminController.php
│   │       │   └── SettingController.php
│   │       ├── Controller.php
│   │       └── ReservationController.php
│   └── Models/
│       ├── Admin.php
│       ├── Reservation.php
│       └── Setting.php
├── database/
│   └── migrations/
│       ├── 2024_01_01_000001_create_reservations_table.php
│       ├── 2024_01_01_000002_create_admins_table.php
│       └── 2024_01_01_000003_create_settings_table.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php (Frontend)
│       │   └── admin.blade.php (Admin Panel)
│       ├── admin/
│       │   ├── auth/
│       │   │   └── login.blade.php
│       │   ├── dashboard.blade.php
│       │   ├── reservations/
│       │   │   ├── index.blade.php
│       │   │   ├── show.blade.php
│       │   │   └── edit.blade.php
│       │   └── settings/
│       │       └── index.blade.php
│       ├── reservasi/
│       │   ├── index.blade.php
│       │   └── success.blade.php
│       └── welcome.blade.php
├── routes/
│   └── web.php
└── .env
```

## 🔧 Instalasi & Menjalankan Project

### Prerequisites
Pastikan sudah terinstall:
- PHP >= 8.2
- Composer
- Node.js & NPM (opsional, untuk development)
- SQLite atau MySQL

### Langkah-langkah Instalasi

#### 1. Clone/Copy Project
```bash
cd /workspace/kretek-sehati
```

#### 2. Install Dependencies
```bash
composer install
```

#### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

#### 4. Konfigurasi Database

**Opsi A: SQLite (Recommended untuk Development)**
```bash
# Buat file database.sqlite di folder database
touch database/database.sqlite

# Pastikan di .env:
DB_CONNECTION=sqlite
# DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD dikosongkan/comment
```

**Opsi B: MySQL**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kretek_sehati
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### 5. Jalankan Migrations
```bash
php artisan migrate
```

#### 6. Seed Data Awal (Optional - Membuat Admin Default)
Buat file `database/seeders/AdminSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@kreteksehati.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }
}
```

Kemudian jalankan:
```bash
php artisan db:seed --class=AdminSeeder
```

#### 7. Jalankan Development Server
```bash
php artisan serve
```

Akses aplikasi di:
- **Frontend:** http://localhost:8000
- **Admin Login:** http://localhost:8000/admin/login

**Login Admin Default:**
- Email: `admin@kreteksehati.com`
- Password: `password123`

## 📱 Fitur WhatsApp Notification

Untuk integrasi notifikasi WhatsApp otomatis saat ada reservasi baru:

### Menggunakan Fonnte API

1. Daftar di [Fonnte](https://fonnte.com/)
2. Dapatkan API Token
3. Update `.env`:
```env
WA_API_TOKEN=your_fonnte_token_here
WA_DESTINATION_NUMBER=6281234567890
```

4. Implementasi di `ReservationController.php`:

```php
private function sendWhatsAppNotification(Reservation $reservation)
{
    $token = env('WA_API_TOKEN');
    $phone = env('WA_DESTINATION_NUMBER');
    
    $message = "🔔 RESERVASI BARU\n\n";
    $message .= "Kode Booking: {$reservation->booking_code}\n";
    $message .= "Nama: {$reservation->full_name}\n";
    $message .= "Tanggal: {$reservation->therapy_date->format('d F Y')}\n";
    $message .= "Jam: {$reservation->therapy_time} WIB\n";
    $message .= "Treatment: {$reservation->treatment_type}\n";
    $message .= "\nSilakan hubungi customer untuk konfirmasi.";
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
            'target' => $phone,
            'message' => $message,
        ),
        CURLOPT_HTTPHEADER => array(
            'Authorization: ' . $token
        ),
    ));
    
    $response = curl_exec($curl);
    curl_close($curl);
    
    return $response;
}
```

## 🎨 Branding & Design

### Logo
- Lingkaran hitam dengan tulisan "mta" putih
- Tagline: "Mending Terapi Aja"

### Warna
- **Primary:** #0EA5A5 (Hijau Toska)
- **Light:** #14B8A6 (Hijau Muda)
- **Dark:** #1F2937 (Abu Gelap)
- **Secondary:** #0D9494

### Font
- **Heading:** Poppins
- **Body:** Inter

## 📊 Tarif Terapi

| Jenis Treatment | Harga |
|----------------|-------|
| Terapi Tulang Belakang | Rp 150.000 |
| Terapi Saraf Kejepit | Rp 200.000 |
| Terapi HNP | Rp 250.000 |
| Terapi Sciatica | Rp 200.000 |
| Terapi Syaraf Tangan | Rp 180.000 |
| Terapi Kaku Leher | Rp 150.000 |
| Terapi Encok/Gout | Rp 180.000 |
| Terapi Rematik | Rp 180.000 |
| Terapi Pegal-Pegal | Rp 150.000 |
| Terapi Keseleo/Terpeleset | Rp 150.000 |
| Terapi Nyeri Lutut | Rp 180.000 |
| Terapi Nyeri Bahu | Rp 180.000 |
| Terapi Migrain/Sakit Kepala | Rp 150.000 |
| Terapi Insomnia/Sulit Tidur | Rp 150.000 |
| Terapi Gangguan Pencernaan | Rp 180.000 |
| Terapi Gangguan Pernapasan | Rp 180.000 |
| Paket Lengkap (Full Body) | Rp 350.000 ⭐ |

## 🔐 Keamanan

- Password hashing dengan bcrypt
- CSRF Protection
- Input validation
- SQL Injection prevention (Eloquent ORM)
- XSS protection (Blade templating)

## 📝 License

© 2024 Kretek Sehati Depok (MTA). All rights reserved.

---

**Developed with ❤️ using Laravel 11**
