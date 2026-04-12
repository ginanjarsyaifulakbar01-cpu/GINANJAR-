@extends('Layout.frontend.app')

@section('content')
<style>
    body { background-color: #f8fafc; color: #1e293b; }

    .detail-container {
        padding-top: 140px;
        padding-bottom: 80px;
        min-height: 100vh;
    }

    .detail-wrapper {
        display: grid;
        grid-template-columns: 400px 1fr;
        gap: 50px;
        background: white;
        padding: 40px;
        border-radius: 32px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.03);
    }

    /* --- SISI KIRI: COVER --- */
    .detail-cover-wrapper {
        width: 100%;
        position: sticky;
        top: 140px;
    }

    .detail-img-container {
        width: 100%;
        aspect-ratio: 3/4;
        background: #f1f5f9;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .detail-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* --- SISI KANAN: INFO --- */
    .badge-cat {
        display: inline-block;
        padding: 6px 16px;
        background: #eff6ff;
        color: #2563eb;
        border-radius: 10px;
        font-weight: 800;
        font-size: 12px;
        text-transform: uppercase;
        margin-bottom: 20px;
    }

    .book-main-title {
        font-size: 40px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
        margin-bottom: 20px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 1px solid #f1f5f9;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .info-label { font-size: 13px; color: #64748b; font-weight: 600; }
    .info-value { font-size: 16px; color: #0f172a; font-weight: 700; }

    .description-box {
        margin-bottom: 40px;
    }

    .description-box h3 {
        font-size: 18px;
        font-weight: 800;
        margin-bottom: 15px;
    }

    .description-box p {
        color: #475569;
        line-height: 1.8;
        font-size: 16px;
    }

    /* --- FORM BOX --- */
    .form-peminjaman-box {
        background: #f8fafc;
        padding: 25px;
        border-radius: 24px;
        border: 2px dashed #e2e8f0;
        margin-bottom: 20px;
    }

    .input-custom {
        width: 100%;
        padding: 12px 15px;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        font-weight: 700;
        margin-top: 8px;
        outline: none;
        transition: 0.3s;
    }
    .input-custom:focus { border-color: #2563eb; box-shadow: 0 0 0 4px #dbeafe; }

    .related-section { margin-top: 60px; }
    .related-title { font-weight: 800; font-size: 24px; margin-bottom: 25px; }

    @media (max-width: 992px) {
        .detail-wrapper { grid-template-columns: 1fr; }
        .detail-cover-wrapper { position: static; max-width: 300px; margin: 0 auto 30px; }
        .book-main-title { font-size: 28px; }
    }
</style>

<div class="container detail-container">
    <nav style="margin-bottom: 30px;">
        <a href="{{ route('katalog') }}" style="text-decoration: none; color: #64748b; font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Katalog
        </a>
    </nav>

    <div class="detail-wrapper">
        <div class="detail-cover-wrapper">
            <div class="detail-img-container">
                @php
                    $imagePath = 'https://via.placeholder.com/400x600?text=No+Cover';
                    if ($buku->cover) {
                        $imagePath = Str::startsWith($buku->cover, 'cover-img') 
                                     ? asset($buku->cover) 
                                     : asset('storage/' . $buku->cover);
                    }
                @endphp
                <img src="{{ $imagePath }}" class="detail-img" alt="{{ $buku->judul }}">
            </div>
            
            <div style="margin-top: 20px; padding: 20px; background: #f8fafc; border-radius: 20px; text-align: center;">
                <span style="display: block; font-size: 13px; color: #64748b; margin-bottom: 5px; font-weight: 600;">Status Ketersediaan</span>
                <div style="font-size: 18px; font-weight: 800; color: {{ $buku->stok > 0 ? '#10b981' : '#ef4444' }}">
                    <i class="fas {{ $buku->stok > 0 ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                    {{ $buku->stok > 0 ? 'Tersedia ' . $buku->stok . ' Buku' : 'Stok Habis' }}
                </div>
            </div>
        </div>

        <div class="detail-content">
            @if(session('success'))
                <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 15px; margin-bottom: 20px; font-weight: 700; border: 1px solid #10b981;">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 15px; margin-bottom: 20px; font-weight: 700; border: 1px solid #ef4444;">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <span class="badge-cat">{{ $buku->category->name ?? 'Umum' }}</span>
            <h1 class="book-main-title">{{ $buku->judul }}</h1>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Penulis</span>
                    <span class="info-value">{{ $buku->penulis ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Penerbit</span>
                    <span class="info-value">{{ $buku->penerbit ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Tahun Terbit</span>
                    <span class="info-value">{{ $buku->tahun_terbit ?? '-' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Kode Buku</span>
                    <span class="info-value">{{ $buku->id }}</span>
                </div>
            </div>

            <div class="description-box">
                <h3>Sinopsis / Deskripsi</h3>
                <p>{{ $buku->deskripsi ?? 'Tidak ada deskripsi untuk buku ini.' }}</p>
            </div>

            @if($buku->stok > 0)
                <form action="{{ route('buku.pinjam', $buku->id) }}" method="POST">
                    @csrf
                    <div class="form-peminjaman-box">
                        <div style="margin-bottom: 20px;">
                            <label class="info-label" style="color: #0f172a; font-size: 15px;">
                                <i class="fas fa-calendar-day text-primary" style="margin-right: 5px;"></i> Rencana Tanggal Pinjam
                            </label>
                            <input type="date" name="tgl_pinjam" class="input-custom" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div>
                            <label class="info-label" style="color: #0f172a; font-size: 15px;">
                                <i class="fas fa-hourglass-half text-primary" style="margin-right: 5px;"></i> Durasi Peminjaman
                            </label>
                            <div style="display: flex; align-items: center; gap: 15px; margin-top: 5px;">
                                <div style="flex: 1;">
                                    <input type="number" name="durasi" class="input-custom" value="7" min="7" required>
                                </div>
                                <div style="font-weight: 800; color: #64748b; padding-top: 8px;">Hari</div>
                            </div>
                        </div>

                        <p style="font-size: 11px; color: #2563eb; margin-top: 15px; font-weight: 700; margin-bottom: 0;">
                            *Mode Testing: Tanggal bisa diatur ke masa lalu untuk simulasi denda.
                        </p>
                    </div>

                    <button type="submit" style="width: 100%; padding: 18px; border-radius: 18px; border: none; background: #2563eb; color: white; font-weight: 800; font-size: 16px; cursor: pointer; transition: 0.3s; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2);">
                        <i class="fas fa-paper-plane" style="margin-right: 10px;"></i> Ajukan Peminjaman Sekarang
                    </button>
                </form>
            @else
                <button disabled style="width: 100%; padding: 18px; border-radius: 18px; border: none; background: #94a3b8; color: white; font-weight: 800; font-size: 16px; cursor: not-allowed;">
                    <i class="fas fa-times-circle" style="margin-right: 10px;"></i> Stok Habis
                </button>
            @endif
        </div>
    </div>

    @if($related_books->count() > 0)
    <div class="related-section">
        <h2 class="related-title">Mungkin Kamu Suka</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
            @foreach($related_books as $rb)
            <a href="{{ route('buku.detail', $rb->id) }}" style="text-decoration: none; background: white; padding: 12px; border-radius: 20px; border: 1px solid #e2e8f0; display: block;">
                <div style="width: 100%; aspect-ratio: 3/4; border-radius: 12px; overflow: hidden; margin-bottom: 12px;">
                    @php
                        $rbPath = $rb->cover 
                                ? (Str::startsWith($rb->cover, 'cover-img') ? asset($rb->cover) : asset('storage/' . $rb->cover)) 
                                : 'https://via.placeholder.com/200x300?text=No+Cover';
                    @endphp
                    <img src="{{ $rbPath }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <h5 style="color: #0f172a; font-weight: 800; font-size: 14px; margin: 0;">{{ Str::limit($rb->judul, 30) }}</h5>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection