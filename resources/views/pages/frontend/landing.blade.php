@extends('Layout.frontend.app')

@section('style')
    <style>
        /* --- Hero Section --- */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 100px;
            background: radial-gradient(circle at 85% 15%, #eff6ff 0%, #ffffff 60%);
        }

        .hero-wrapper {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 60px;
            align-items: center;
        }

        .hero-text h1 {
            font-size: 56px;
            font-weight: 800;
            color: #111827;
            line-height: 1.15;
            margin-bottom: 24px;
            letter-spacing: -1.5px;
        }

        .hero-text h1 span.highlight {
            color: #2563eb;
        }

        .hero-text p {
            font-size: 19px;
            color: #6b7280;
            margin-bottom: 40px;
            max-width: 580px;
            line-height: 1.7;
        }

        .badge-online {
            background: #dbeafe;
            color: #1e40af;
            padding: 6px 16px;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 25px;
            border: 1px solid #bfdbfe;
        }

        .badge-online i {
            font-size: 10px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.4;
            }
        }

        .btn-group {
            display: flex;
            gap: 20px;
        }

        .btn-hero-primary {
            background: #2563eb;
            color: white;
            padding: 16px 35px;
            border-radius: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
            transition: 0.3s;
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            background: #1e40af;
        }

        .btn-hero-secondary {
            background: white;
            color: #1f2937;
            padding: 16px 35px;
            border-radius: 12px;
            font-weight: 600;
            border: 1px solid #d1d5db;
            transition: 0.3s;
        }

        .hero-stats {
            display: flex;
            gap: 50px;
            margin-top: 60px;
            padding-top: 30px;
            border-top: 1px solid #f3f4f6;
        }

        .stat-item h3 {
            font-size: 28px;
            font-weight: 800;
            color: #1e40af;
        }

        /* --- Visual Art --- */
        .hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .main-book-card {
            background: white;
            width: 280px;
            height: 380px;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 1px solid #f3f4f6;
            transform: rotate(-5px);
            animation: floatMain 6s infinite ease-in-out;
        }

        .main-book-card i {
            font-size: 80px;
            color: #f97316;
            margin-bottom: 25px;
        }

        .decor-element {
            position: absolute;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            animation: floatDecor 8s infinite ease-in-out;
        }

        .decor-1 {
            width: 80px;
            height: 100px;
            top: 10%;
            right: 15%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
            font-size: 30px;
        }

        @keyframes floatMain {

            0%,
            100% {
                transform: translateY(0) rotate(-5px);
            }

            50% {
                transform: translateY(-15px) rotate(-3px);
            }
        }

        @keyframes floatDecor {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @media (max-width: 1024px) {
            .hero-wrapper {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero-visual {
                order: -1;
                height: 400px;
            }

            .btn-group,
            .hero-stats {
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')
    <section class="hero">
        <div class="container">
            <div class="hero-wrapper">
                <div class="hero-text">
                    <div class="badge-online">
                        <i class="fas fa-circle"></i> PERPUSTAKAAN ONLINE TERPERCAYA
                    </div>
                    <h1>Platform <span class="highlight">Literasi Digital</span> Untuk Masa Depan</h1>
                    <p>Akses ribuan koleksi buku, jurnal, dan referensi tanpa batas, langsung dari genggamanmu. Mari bawa
                        pengetahuan ke era baru.</p>

                    <div class="btn-group">
                        {{-- Logika Pintar: Jika sudah login langsung ke Katalog, jika belum ke Login --}}
                        <a href="{{ Auth::check() ? route('katalog') : route('login') }}" class="btn-hero-primary">
                            {{ Auth::check() ? 'Lanjut Membaca' : 'Mulai Membaca' }}
                            <i class="fas fa-arrow-right"></i>
                        </a>

                        {{-- Tombol Lihat Koleksi juga arahkan ke Katalog --}}
                        <a href="{{ route('katalog') }}" class="btn-hero-secondary">Lihat Koleksi</a>
                    </div>

                    <div class="hero-stats">
                        <div class="stat-item">
                           
                          
                        </div>
                        <div class="stat-item">
                           
                        </div>
                        <div class="stat-item">
                           
                           
                        </div>
                    </div>
                </div>
                <div class="hero-visual">
                    <div class="main-book-card">
                        <i class="fas fa-book-open"></i>
                        <h4>Koleksi E-Book</h4>
                    </div>
                    <div class="decor-element decor-1">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection