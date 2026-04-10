@extends('Layout.frontend.app')

@section('content')
<style>
    /* Sinkronisasi Font & Warna Utama */
    body {
        color: #1f2937;
        background-color: #f8fafc;
    }

    /* --- HERO SECTION --- */
    .hero {
        padding: 100px 0 60px;
        text-align: center;
        background: radial-gradient(circle at 85% 15%, #eff6ff 0%, #ffffff 60%);
        border-bottom: 1px solid #e2e8f0;
    }

    .hero h1 { 
        font-size: 48px; 
        margin-bottom: 15px; 
        font-weight: 800; 
        color: #0f172a; 
        letter-spacing: -1.5px;
    }
    .hero h1 span { color: #2563eb; }
    
    .hero p { 
        font-size: 18px; 
        color: #64748b;
        margin-bottom: 40px; 
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
    
    /* SEARCH BAR */
    .search-wrapper {
        max-width: 700px;
        margin: auto;
        padding: 0 20px;
    }
    .search-bar {
        background: white;
        padding: 8px;
        border-radius: 20px;
        display: flex;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        transition: 0.3s;
    }
    .search-bar:focus-within {
        border-color: #2563eb;
        box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.1);
    }
    .search-bar input {
        border: none;
        padding: 12px 25px;
        flex-grow: 1;
        outline: none;
        font-size: 16px;
        color: #1e293b;
        background: transparent;
    }
    .search-bar button {
        background: #1e293b; 
        border: none;
        color: white;
        padding: 0 30px;
        border-radius: 15px;
        cursor: pointer;
        font-weight: 700;
        transition: 0.3s;
    }
    .search-bar button:hover { background: #2563eb; transform: scale(1.02); }

    /* --- SECTION CATALOG --- */
    .catalog-container { padding: 60px 0; }

    .section-title { 
        margin-bottom: 40px; 
        display: flex; 
        justify-content: space-between; 
        align-items: center; 
    }
    .section-title h2 {
        font-weight: 800;
        color: #0f172a;
        font-size: 30px;
    }
    
    /* GRID & CARDS */
    .grid-buku {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 35px;
    }
    .card-buku {
        background: white;
        border-radius: 28px;
        padding: 15px;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
    }
    .card-buku:hover { 
        transform: translateY(-12px); 
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
    }

    .cover-wrapper {
        width: 100%;
        aspect-ratio: 3/4;
        background: #f8fafc;
        border-radius: 20px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        border: 1px solid #f1f5f9;
    }
    .img-cover {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: 0.5s;
    }
    .card-buku:hover .img-cover { transform: scale(1.1); }

    .category-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(8px);
        color: #2563eb;
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    .card-buku h4 {
        color: #0f172a;
        font-weight: 800;
        margin-bottom: 15px;
        font-size: 18px;
        padding: 0 5px;
        line-height: 1.4;
    }
    
    .card-meta {
        margin-top: auto;
        padding: 15px 5px 5px;
        display: flex;
        justify-content: center; /* Center status karena tombol dihapus */
        align-items: center;
        border-top: 1px solid #f1f5f9;
    }

    .stok-status {
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 6px;
    }
</style>

<div class="hero">
    <div class="container">
        <div style="background: #dbeafe; color: #1e40af; padding: 6px 16px; border-radius: 30px; display: inline-flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 700; margin-bottom: 25px;">
            <i class="fas fa-sparkles"></i> WELCOME, {{ Auth::user()->name }}
        </div>
        <h1>Temukan <span>Inspirasi</span> Membacamu</h1>
        <p>Jelajahi ribuan koleksi buku terbaik yang tersedia di perpustakaan kami.</p>
        
        <div class="search-wrapper">
            <form action="{{ route('home') }}" method="GET" class="search-bar">
                <input type="text" name="search" placeholder="Cari judul buku, penulis, atau kategori..." value="{{ request('search') }}">
                <button type="submit">Cari Buku</button>
            </form>
        </div>
    </div>
</div>

<div class="container catalog-container">
    <div class="section-title">
        <h2>Koleksi Buku</h2>
        <div style="background: white; padding: 8px 16px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 14px; font-weight: 700;">
            <span style="color: #64748b;">Total:</span> {{ $bukus->count() }} Judul
        </div>
    </div>

    <div class="grid-buku">
        @forelse($bukus as $buku)
       <div class="card-buku">
    <div class="cover-wrapper">
        <span class="category-badge">{{ $buku->category->name ?? 'Umum' }}</span>
        
        {{-- LOGIKA DETEKSI PATH FOTO --}}
        @php
            $imagePath = 'https://via.placeholder.com/300x400?text=No+Cover';
            if ($buku->cover) {
                if (Str::startsWith($buku->cover, 'cover-img')) {
                    $imagePath = asset($buku->cover);
                } else {
                    $imagePath = asset('storage/' . $buku->cover);
                }
            }
        @endphp

        <img src="{{ $imagePath }}" 
             class="img-cover" 
             alt="{{ $buku->judul }}"
             onerror="this.onerror=null;this.src='https://via.placeholder.com/300x400?text=Path+Salah';">
    </div>
    
    <h4>{{ Str::limit($buku->judul, 45) }}</h4>
    
    <div class="card-meta">
        <div class="stok-status" style="color: {{ $buku->stok > 0 ? '#10b981' : '#ef4444' }}">
            <i class="fas {{ $buku->stok > 0 ? 'fa-check-double' : 'fa-times-circle' }}"></i>
            {{ $buku->stok > 0 ? 'Tersedia ' . $buku->stok : 'Stok Habis' }}
        </div>
    </div>
</div>
        @empty
        <div style="grid-column: 1/-1; text-align: center; padding: 100px 0; background: white; border-radius: 30px; border: 2px dashed #e2e8f0;">
            <img src="https://illustrations.popsy.co/gray/falling.svg" style="width: 200px; margin-bottom: 20px;">
            <h3 style="color: #1e293b; font-weight: 800;">Buku Tidak Ditemukan</h3>
            <p style="color: #64748b;">Maaf bro, koleksi yang ente cari belum tersedia di rak kami.</p>
            <a href="{{ route('home') }}" style="color: #2563eb; font-weight: 700; text-decoration: none; display: inline-block; margin-top: 15px;">Tampilkan Semua Buku</a>
        </div>
        @endforelse
    </div>
</div>
@endsection