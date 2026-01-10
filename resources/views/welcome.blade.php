<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BangunanPro - Sistem ERP Toko Bangunan</title>
    <meta name="description" content="Kelola toko bangunan Anda dengan mudah. POS, inventaris, laporan, dan notifikasi WhatsApp dalam satu aplikasi.">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🔧</text></svg>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: { 50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd', 400: '#60a5fa', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a' },
                        accent: { 500: '#f97316', 600: '#ea580c' }
                    }
                }
            }
        }
    </script>
    
    <style>
        .gradient-bg { background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%); }
        .hero-pattern { background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); }
    </style>
</head>
<body class="font-sans antialiased bg-white text-gray-900">
    <!-- Navigation -->
    <nav class="fixed top-0 w-full bg-white/90 backdrop-blur-md shadow-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <span class="text-3xl">🔧</span>
                    <span class="text-xl font-bold text-primary-700">BangunanPro</span>
                </div>
                <div class="hidden md:flex items-center gap-8">
                    <a href="#fitur" class="text-gray-600 hover:text-primary-600 transition">Fitur</a>
                    <a href="#harga" class="text-gray-600 hover:text-primary-600 transition">Harga</a>
                    <a href="#kontak" class="text-gray-600 hover:text-primary-600 transition">Kontak</a>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-primary-600 font-medium transition">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-primary-600 text-white px-5 py-2.5 rounded-lg font-medium hover:bg-primary-700 transition shadow-lg shadow-primary-500/30">
                        Coba Gratis
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-bg hero-pattern pt-32 pb-20 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight">
                        Kelola Toko Bangunan <span class="text-yellow-300">Lebih Mudah</span>
                    </h1>
                    <p class="mt-6 text-xl text-blue-100 leading-relaxed">
                        Sistem ERP lengkap untuk toko bangunan. POS, inventaris, pembelian, laporan keuangan, dan notifikasi WhatsApp dalam satu aplikasi.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-primary-700 font-bold rounded-xl hover:bg-blue-50 transition shadow-xl text-lg">
                            Mulai 30 Hari Gratis
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="#demo" class="inline-flex items-center justify-center px-8 py-4 border-2 border-white/50 text-white font-semibold rounded-xl hover:bg-white/10 transition text-lg">
                            Lihat Demo
                        </a>
                    </div>
                    <div class="mt-8 flex items-center gap-6 text-sm text-blue-100">
                        <span class="flex items-center gap-2">✓ Tanpa kartu kredit</span>
                        <span class="flex items-center gap-2">✓ Setup 5 menit</span>
                        <span class="flex items-center gap-2">✓ Support lokal</span>
                    </div>
                </div>
                <div class="relative hidden lg:block">
                    <div class="bg-white rounded-2xl shadow-2xl p-4 transform rotate-2 hover:rotate-0 transition-transform duration-300">
                        <div class="bg-gray-100 rounded-lg aspect-video flex items-center justify-center">
                            <div class="text-center p-8">
                                <span class="text-6xl">📊</span>
                                <p class="mt-4 text-gray-600 font-medium">Dashboard Preview</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Fitur Lengkap untuk Toko Bangunan</h2>
                <p class="mt-4 text-xl text-gray-600">Semua yang Anda butuhkan dalam satu aplikasi</p>
            </div>
            
            <div class="mt-16 grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition group">
                    <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition">💰</div>
                    <h3 class="mt-6 text-xl font-bold text-gray-900">Point of Sale (POS)</h3>
                    <p class="mt-3 text-gray-600">Kasir cepat dengan pencarian produk, diskon, dan cetak struk otomatis.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition group">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition">📦</div>
                    <h3 class="mt-6 text-xl font-bold text-gray-900">Manajemen Inventaris</h3>
                    <p class="mt-3 text-gray-600">Lacak stok real-time, peringatan stok rendah, dan riwayat pergerakan.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition group">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition">📊</div>
                    <h3 class="mt-6 text-xl font-bold text-gray-900">Laporan Keuangan</h3>
                    <p class="mt-3 text-gray-600">Laporan penjualan, laba rugi, dan analisis produk terlaris.</p>
                </div>
                
                <!-- Feature 4 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition group">
                    <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition">🛒</div>
                    <h3 class="mt-6 text-xl font-bold text-gray-900">Pembelian & Supplier</h3>
                    <p class="mt-3 text-gray-600">Kelola pembelian, terima barang, dan pantau hutang supplier.</p>
                </div>
                
                <!-- Feature 5 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition group">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition">📱</div>
                    <h3 class="mt-6 text-xl font-bold text-gray-900">Notifikasi WhatsApp</h3>
                    <p class="mt-3 text-gray-600">Kirim struk dan notifikasi otomatis ke pelanggan via WhatsApp.</p>
                </div>
                
                <!-- Feature 6 -->
                <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition group">
                    <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center text-2xl group-hover:scale-110 transition">📷</div>
                    <h3 class="mt-6 text-xl font-bold text-gray-900">Scan Barcode</h3>
                    <p class="mt-3 text-gray-600">Scan barcode dengan kamera HP, tidak perlu scanner mahal.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="harga" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Harga Transparan</h2>
                <p class="mt-4 text-xl text-gray-600">Pilih paket sesuai kebutuhan bisnis Anda</p>
            </div>
            
            <div class="mt-16 grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Free Plan -->
                <div class="bg-white border-2 border-gray-200 rounded-2xl p-8 hover:border-primary-300 transition">
                    <h3 class="text-xl font-bold text-gray-900">Gratis</h3>
                    <div class="mt-4">
                        <span class="text-4xl font-extrabold">Rp 0</span>
                        <span class="text-gray-500">/bulan</span>
                    </div>
                    <ul class="mt-8 space-y-4 text-gray-600">
                        <li class="flex items-center gap-3">✓ 1 Pengguna</li>
                        <li class="flex items-center gap-3">✓ 100 Produk</li>
                        <li class="flex items-center gap-3">✓ POS Dasar</li>
                        <li class="flex items-center gap-3 text-gray-400">✗ Laporan</li>
                        <li class="flex items-center gap-3 text-gray-400">✗ WhatsApp</li>
                    </ul>
                    <a href="{{ route('register') }}" class="mt-8 block text-center py-3 px-6 border-2 border-primary-600 text-primary-600 font-semibold rounded-xl hover:bg-primary-50 transition">
                        Mulai Gratis
                    </a>
                </div>
                
                <!-- Business Plan (Featured) -->
                <div class="bg-primary-600 text-white rounded-2xl p-8 transform md:-translate-y-4 shadow-2xl relative">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-yellow-400 text-yellow-900 text-sm font-bold px-4 py-1 rounded-full">
                        POPULER
                    </div>
                    <h3 class="text-xl font-bold">Usaha</h3>
                    <div class="mt-4">
                        <span class="text-4xl font-extrabold">Rp 199rb</span>
                        <span class="text-blue-200">/bulan</span>
                    </div>
                    <ul class="mt-8 space-y-4 text-blue-100">
                        <li class="flex items-center gap-3">✓ 3 Pengguna</li>
                        <li class="flex items-center gap-3">✓ 2000 Produk</li>
                        <li class="flex items-center gap-3">✓ POS + Inventaris</li>
                        <li class="flex items-center gap-3">✓ Semua Laporan</li>
                        <li class="flex items-center gap-3">✓ PDF & Export</li>
                    </ul>
                    <a href="{{ route('register') }}" class="mt-8 block text-center py-3 px-6 bg-white text-primary-600 font-bold rounded-xl hover:bg-blue-50 transition">
                        Coba 30 Hari
                    </a>
                </div>
                
                <!-- Pro Plan -->
                <div class="bg-white border-2 border-gray-200 rounded-2xl p-8 hover:border-primary-300 transition">
                    <h3 class="text-xl font-bold text-gray-900">Profesional</h3>
                    <div class="mt-4">
                        <span class="text-4xl font-extrabold">Rp 399rb</span>
                        <span class="text-gray-500">/bulan</span>
                    </div>
                    <ul class="mt-8 space-y-4 text-gray-600">
                        <li class="flex items-center gap-3">✓ 10 Pengguna</li>
                        <li class="flex items-center gap-3">✓ Unlimited Produk</li>
                        <li class="flex items-center gap-3">✓ Semua Fitur Usaha</li>
                        <li class="flex items-center gap-3">✓ WhatsApp Otomatis</li>
                        <li class="flex items-center gap-3">✓ Support Prioritas</li>
                    </ul>
                    <a href="{{ route('register') }}" class="mt-8 block text-center py-3 px-6 border-2 border-primary-600 text-primary-600 font-semibold rounded-xl hover:bg-primary-50 transition">
                        Coba 30 Hari
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 gradient-bg">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white">Siap Tingkatkan Bisnis Anda?</h2>
            <p class="mt-4 text-xl text-blue-100">Mulai 30 hari gratis, tanpa kartu kredit.</p>
            <a href="{{ route('register') }}" class="mt-8 inline-flex items-center px-8 py-4 bg-white text-primary-700 font-bold rounded-xl hover:bg-blue-50 transition shadow-xl text-lg">
                Daftar Sekarang
                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer id="kontak" class="bg-gray-900 text-gray-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">🔧</span>
                        <span class="text-xl font-bold text-white">BangunanPro</span>
                    </div>
                    <p class="mt-4 text-gray-500 max-w-md">
                        Sistem ERP lengkap untuk toko bangunan Indonesia. Dibuat dengan ❤️ untuk membantu UMKM.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">Produk</h4>
                    <ul class="space-y-2">
                        <li><a href="#fitur" class="hover:text-white transition">Fitur</a></li>
                        <li><a href="#harga" class="hover:text-white transition">Harga</a></li>
                        <li><a href="#" class="hover:text-white transition">Dokumentasi</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">Kontak</h4>
                    <ul class="space-y-2">
                        <li>📧 support@bangunanpro.id</li>
                        <li>📱 +62 812-xxxx-xxxx</li>
                        <li>📍 Indonesia</li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-800 text-center text-sm text-gray-500">
                © {{ date('Y') }} BangunanPro. All rights reserved.
            </div>
        </div>
    </footer>
</body>
</html>
