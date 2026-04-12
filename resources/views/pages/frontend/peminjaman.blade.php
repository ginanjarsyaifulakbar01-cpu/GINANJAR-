@extends('Layout.frontend.app')

@section('content')
<style>
    /* 1. LAYOUT & TYPOGRAPHY */
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');

    .content-wrapper {
        padding-top: 140px; 
        padding-bottom: 80px;
        background-color: #f8f9fa; 
        min-height: 100vh;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .header-info { margin-bottom: 40px; }
    .header-info h2 { 
        font-weight: 800; 
        color: #1a1a1a; 
        letter-spacing: -1px; 
    }

    /* 2. JCARD DESIGN (Horizontal Style) */
    .j-card {
        background: #ffffff;
        border: 1px solid #eef0f2;
        border-radius: 24px;
        padding: 24px;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        text-decoration: none !important;
        display: block;
        height: 100%;
    }
    .j-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.06);
        border-color: #2563eb;
    }

    /* Thumbnail / Cover */
    .j-cover-wrapper {
        flex-shrink: 0;
    }
    .j-cover {
        width: 100px;
        height: 145px;
        object-fit: cover;
        border-radius: 16px;
        background: #f1f5f9;
        box-shadow: 5px 10px 20px rgba(0,0,0,0.08);
    }

    /* Status Badges */
    .j-badge {
        display: inline-flex;
        align-items: center;
        font-size: 10px;
        font-weight: 800;
        padding: 5px 14px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }
    .j-badge-aktif { background: #e0f2fe; color: #0369a1; }
    .j-badge-telat { background: #fee2e2; color: #dc2626; }
    .j-badge-pending { background: #fef3c7; color: #b45309; }
    .j-badge-selesai { background: #f1f5f9; color: #64748b; }

    /* Meta Info (Grid) */
    .j-meta-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-top: 20px;
        padding-top: 15px;
        border-top: 1px solid #f1f5f9;
    }
    .j-meta-item label {
        display: block;
        font-size: 9px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        margin-bottom: 2px;
    }
    .j-meta-item p {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .j-btn-detail {
        margin-top: 15px;
        font-size: 13px;
        font-weight: 700;
        color: #2563eb;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: 0.3s;
    }
    .j-card:hover .j-btn-detail {
        gap: 10px;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: #94a3b8;
    }
</style>

<div class="content-wrapper">
    <div class="container">
        
        <div class="header-info">
            <h2>Peminjaman Saya</h2>
            <p class="text-muted">Semua riwayat peminjaman buku dalam satu halaman.</p>
        </div>

        <div class="row g-4">
            @forelse($peminjaman as $p)
                <div class="col-xl-6">
                    <a href="{{ route('peminjaman.detail', $p->id) }}" class="j-card">
                        <div class="d-flex align-items-start gap-4">
                            <div class="j-cover-wrapper">
                                @php
                                    $cover = $p->buku->cover 
                                            ? (Str::startsWith($p->buku->cover, 'cover-img') ? asset($p->buku->cover) : asset('storage/' . $p->buku->cover)) 
                                            : 'https://ui-avatars.com/api/?name='.urlencode($p->buku->judul).'&background=f1f5f9&color=64748b';
                                @endphp
                                <img src="{{ $cover }}" class="j-cover" alt="Book Cover">
                            </div>

                            <div class="flex-grow-1">
                                @php
                                    $isLate = $p->status == 'pinjam' && \Carbon\Carbon::now()->gt(\Carbon\Carbon::parse($p->tgl_kembali));
                                @endphp

                                <div class="d-flex justify-content-between align-items-start">
                                    @if($p->status == 'dikembalikan')
                                        <span class="j-badge j-badge-selesai">Selesai</span>
                                    @elseif($isLate)
                                        <span class="j-badge j-badge-telat">Terlambat</span>
                                    @elseif($p->status == 'pending')
                                        <span class="j-badge j-badge-pending">Menunggu Verifikasi</span>
                                    @else
                                        <span class="j-badge j-badge-aktif">Aktif</span>
                                    @endif
                                    <span class="text-muted small">#{{ $p->id }}</span>
                                </div>

                                <h5 class="fw-bold mb-1 text-dark" style="line-height: 1.4;">{{ Str::limit($p->buku->judul, 55) }}</h5>
                                
                                <div class="j-meta-grid">
                                    <div class="j-meta-item">
                                        <label>Tanggal Pinjam</label>
                                        <p>{{ $p->tgl_pinjam ? \Carbon\Carbon::parse($p->tgl_pinjam)->format('d M Y') : '-' }}</p>
                                    </div>
                                    <div class="j-meta-item">
                                        <label>{{ $p->status == 'dikembalikan' ? 'Sudah Kembali' : 'Batas Waktu' }}</label>
                                        <p class="{{ $isLate ? 'text-danger' : 'text-primary' }}">
                                            {{ $p->tgl_kembali ? \Carbon\Carbon::parse($p->tgl_kembali)->format('d M Y') : '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="j-btn-detail">
                                    Lihat Detail Transaksi <i class="bi bi-arrow-right"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <i class="bi bi-inboxes mb-3 d-block" style="font-size: 40px;"></i>
                        <p>Belum ada data transaksi peminjaman.</p>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
@endsection