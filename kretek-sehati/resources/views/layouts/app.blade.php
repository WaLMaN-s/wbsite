<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kretek Sehati Depok - Terapi Tulang dan Otot')</title>
    <meta name="description" content="Layanan terapi tulang dan otot profesional di Kretek Sehati Depok. Mending Terapi Aja!">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0EA5A5',
                        primaryLight: '#14B8A6',
                        dark: '#1F2937',
                        secondary: '#0D9494',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    @stack('styles')
</head>
<body class="font-sans text-gray-700 antialiased">
    <!-- Navigation -->
    <nav class="bg-white shadow-md fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-black rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-sm">mta</span>
                        </div>
                        <div>
                            <p class="font-heading font-semibold text-dark">Kretek Sehati Depok</p>
                            <p class="text-xs text-primary">Mending Terapi Aja</p>
                        </div>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}#home" class="text-gray-600 hover:text-primary transition">Beranda</a>
                    <a href="{{ route('home') }}#about" class="text-gray-600 hover:text-primary transition">Tentang Kami</a>
                    <a href="{{ route('home') }}#services" class="text-gray-600 hover:text-primary transition">Layanan</a>
                    <a href="{{ route('home') }}#pricing" class="text-gray-600 hover:text-primary transition">Tarif</a>
                    <a href="{{ route('home') }}#location" class="text-gray-600 hover:text-primary transition">Lokasi</a>
                    <a href="{{ route('reservasi.index') }}" class="bg-primary hover:bg-secondary text-white px-5 py-2 rounded-lg font-medium transition">
                        Reservasi Sekarang
                    </a>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-gray-600 hover:text-primary focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="px-4 py-3 space-y-3">
                <a href="{{ route('home') }}#home" class="block text-gray-600 hover:text-primary">Beranda</a>
                <a href="{{ route('home') }}#about" class="block text-gray-600 hover:text-primary">Tentang Kami</a>
                <a href="{{ route('home') }}#services" class="block text-gray-600 hover:text-primary">Layanan</a>
                <a href="{{ route('home') }}#pricing" class="block text-gray-600 hover:text-primary">Tarif</a>
                <a href="{{ route('home') }}#location" class="block text-gray-600 hover:text-primary">Lokasi</a>
                <a href="{{ route('reservasi.index') }}" class="block bg-primary text-white text-center px-4 py-2 rounded-lg">Reservasi</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-12 pb-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <!-- Branding -->
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                            <span class="text-black font-bold">mta</span>
                        </div>
                        <div>
                            <h3 class="font-heading font-semibold text-lg">Kretek Sehati Depok</h3>
                            <p class="text-primary text-sm">Mending Terapi Aja</p>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm">
                        Layanan terapi tulang dan otot profesional dengan terapis berpengalaman untuk kesehatan Anda.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="font-heading font-semibold mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('home') }}#home" class="hover:text-primary transition">Beranda</a></li>
                        <li><a href="{{ route('home') }}#services" class="hover:text-primary transition">Layanan</a></li>
                        <li><a href="{{ route('home') }}#pricing" class="hover:text-primary transition">Tarif</a></li>
                        <li><a href="{{ route('reservasi.index') }}" class="hover:text-primary transition">Reservasi</a></li>
                    </ul>
                </div>
                
                <!-- Contact -->
                <div>
                    <h4 class="font-heading font-semibold mb-4">Kontak Kami</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li class="flex items-start space-x-2">
                            <svg class="w-5 h-5 mt-0.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Depok, Jawa Barat</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>0812-3456-7890</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Buka Setiap Hari: 08:00 - 20:00</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 pt-6 text-center text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} Kretek Sehati Depok (MTA). All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>
