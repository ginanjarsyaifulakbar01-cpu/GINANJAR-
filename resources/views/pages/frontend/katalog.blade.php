@extends('Layout.frontend.app')

@section('content')
<style>
    /* 1. Background & Base */
    body { background-color: #f8fafc; color: #1e293b; } 

    .catalog-container { 
        padding-top: 120px; 
        padding-bottom: 80px; 
        min-height: 100vh; 
    }
    
    .catalog-wrapper {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 32px;
        align-items: start;
    }

    /* --- SIDEBAR KATEGORI (Premium Look) --- */
    .sidebar-kategori {
        background: white;
        border-radius: 24px;
        padding: 24px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.02);
        position: sticky;
        top: 120px;
    }
    .sidebar-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 800;
        font-size: 18px;
        margin-bottom: 24px;
        color: #0f172a;
    }
    .sidebar-title i { color: #2563eb; }

    .kategori-list { list-style: none; padding: 0; }
    .kategori-item { margin-bottom: 6px; }
    .kategori-item a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border-radius: 14px;
        color: #64748b;
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        transition: 0.3s;
    }
    .kategori-item.active a {
        background: #2563eb;
        color: white;
        box-shadow: 0 8px 15px -3px rgba(37, 99, 235, 0.25);
    }
    .kategori-item a:hover:not(.active a) {
        background: #f1f5f9;
        color: #2563eb;
        transform: translateX(5px);
    }

    /* --- TOP SEARCH --- */
    .top-filter {
        background: white;
        padding: 10px;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        display: flex;
        gap: 10px;
        margin-bottom: 35px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .search-wrapper {
        flex: 1;
        display: flex;
        align-items: center;
        background: #f8fafc;
        padding: 0 20px;
        border-radius: 15px;
    }
    .search-wrapper input {
        border: none;
        background: transparent;
        padding: 14px;
        width: 100%;
        outline: none;
        font-weight: 600;
        color: #1e293b;
    }
    .btn-cari {
        background: #1e293b;
        color: white;
        border: none;
        padding: 0 30px;
        border-radius: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: 0.3s;
    }
    .btn-cari:hover { background: #2563eb; }

    /* --- GRID & CARD BUKU (Optimized) --- */
    .grid-buku {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 25px;
    }
    .card-buku {
        background: white;
        border-radius: 24px;
        padding: 15px;
        border: 1px solid #f1f5f9;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .card-buku:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08);
        border-color: #dbeafe;
    }

    /* FIX COVER: Agar terlihat semua tanpa terpotong */
    .book-cover-wrapper {
        width: 100%;
        height: 300px; /* Tinggi proporsional buku */
        border-radius: 18px;
        overflow: hidden;
        margin-bottom: 15px;
        background: #f8fafc; /* Background buat sela-sela cover */
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
    }
    .book-cover {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain; /* Kunci agar cover terlihat full */
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .book-info { flex-grow: 1; display: flex; flex-direction: column; }
    .book-category {
        font-size: 10px;
        font-weight: 800;
        color: #2563eb;
        text-transform: uppercase;
        background: #eff6ff;
        padding: 4px 10px;
        border-radius: 8px;
        display: inline-block;
        margin-bottom: 10px;
        width: fit-content;
    }
    .book-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 15px;
        line-height: 1.4;
    }

    .book-meta {
        margin-top: auto;
        padding-top: 15px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .stock-label {
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
    }
    .stock-value {
        color: #10b981;
        font-weight: 800;
    }

    @media (max-width: 1024px) {
        .catalog-wrapper { grid-template-columns: 1fr; }
        .sidebar-kategori { display: none; }
        .catalog-container { padding-top: 100px; }
    }
</style>

<div class="container catalog-container">
    <div class="catalog-wrapper">
        
        <aside class="sidebar-kategori">
            <div class="sidebar-title">
                <i class="fas fa-grid-2"></i> Kategori
            </div>
            <ul class="kategori-list">
                <li class="kategori-item {{ !request('category') ? 'active' : '' }}">
                    <a href="{{ route('katalog') }}">
                        <i class="fas fa-layer-group"></i> Semua Koleksi
                    </a>
                </li>
                @foreach($categories as $cat)
                    <li class="kategori-item {{ request('category') == $cat->id ? 'active' : '' }}">
                        <a href="{{ route('katalog', ['category' => $cat->id]) }}">
                            <i class="{{ $cat->icon }}"></i> {{ $cat->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        <div class="main-catalog">
            <form action="{{ route('katalog') }}" method="GET" class="top-filter">
                <div class="search-wrapper">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau penulis buku...">
                </div>
                <button type="submit" class="btn-cari">Cari</button>
            </form>

            <div class="grid-buku">
                @forelse($bukus as $buku)
                <a href="{{ route('buku.detail', $buku->id) }}" class="card-buku">
                    <div class="book-cover-wrapper">
                        {{-- FIX PATH: Gunakan asset() karena di seeder kita simpan cover-img/x.jpg di folder public --}}
                        <img src="{{ $buku->cover ? asset($buku->cover) : 'https://via.placeholder.com/300x450?text=No+Cover' }}" 
                             class="book-cover" alt="{{ $buku->judul }}">
                    </div>
                    
                    <div class="book-info">
                        <span class="book-category">{{ $buku->category->name ?? 'Umum' }}</span>
                        <h4 class="book-title">{{ Str::limit($buku->judul, 40) }}</h4>
                        
                        <div class="book-meta">
                            <span class="stock-label">Tersedia</span>
                            <span class="stock-value">{{ $buku->stok }} Buku</span>
                        </div>
                    </div>
                </a>
                @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 100px 0; background: white; border-radius: 30px;">
                    <img src="https://illustrations.popsy.co/gray/opening-a-window.svg" style="width: 200px; margin-bottom: 20px;">
                    <h3 style="color: #0f172a; font-weight: 800;">Buku Tidak Ditemukan</h3>
                    <p style="color: #64748b;">Coba cari dengan kata kunci lain atau pilih kategori lain.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection