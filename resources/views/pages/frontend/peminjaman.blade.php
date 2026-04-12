@extends('Layout.frontend.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary: #2563eb;
        --primary-soft: #eff6ff;
        --bg-body: #f8fafc;
        --danger: #ef4444;
        --success: #10b981;
        --warning: #f59e0b;
        --slate-600: #475569;
        --slate-900: #0f172a;
    }

    body { 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        background-color: var(--bg-body); 
        color: var(--slate-900); 
    }

    .content-wrapper { padding: 140px 0 80px; }

    /* --- Card Style --- */
    .loan-card {
        background: #ffffff;
        border-radius: 28px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .loan-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.08);
        border-color: var(--primary);
    }

    .book-cover-wrapper { position: relative; flex-shrink: 0; }
    .book-cover {
        width: 110px;
        height: 160px;
        object-fit: cover;
        border-radius: 18px;
        box-shadow: 0 12px 20px -5px rgba(0,0,0,0.15);
    }

    /* Badge Terlambat Melayang */
    .late-indicator {
        position: absolute;
        top: -12px;
        left: -12px;
        background: var(--danger);
        color: white;
        padding: 6px 14px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.5px;
        box-shadow: 0 8px 15px rgba(239, 68, 68, 0.3);
        z-index: 2;
    }

    /* Badge Status Modern */
    .status-badge {
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .badge-pending { background: #fffbeb; color: #92400e; }
    .badge-active { background: #f0fdf4; color: #166534; }
    .badge-late { background: #fef2f2; color: #991b1b; }
    .badge-process { background: #f0f9ff; color: #075985; }
    .badge-done { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

    /* Grid Info */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        background: #f1f5f9;
        padding: 14px;
        border-radius: 20px;
        margin-top: 18px;
    }
    .info-label { display: block; font-size: 9px; color: #64748b; font-weight: 800; text-transform: uppercase; margin-bottom: 2px; }
    .info-value { font-size: 12px; color: var(--slate-900); font-weight: 700; }

    /* Button Action */
    .btn-action-group { display: flex; gap: 10px; margin-top: auto; padding-top: 22px; }
    .btn-main {
        flex: 1;
        padding: 14px;
        border-radius: 16px;
        font-weight: 700;
        font-size: 13px;
        transition: 0.3s;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none !important;
    }
    .btn-primary-custom { background: var(--primary); color: white !important; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); }
    .btn-danger-custom { background: var(--danger); color: white !important; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2); }
    .btn-outline-custom { background: #fff; color: var(--slate-600) !important; border: 1px solid #e2e8f0; }
    .btn-main:hover { transform: translateY(-2px); opacity: 0.95; }

    /* --- Modal Style --- */
    .modal-content { border-radius: 32px; border: none; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.2); }
    .fine-alert { 
        background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%); 
        border: 1px dashed #fda4af; 
        padding: 24px; 
        border-radius: 24px; 
        text-align: center;
    }
    .bank-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 20px;
        margin-top: 20px;
    }
    .form-control-custom {
        padding: 12px 16px;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }
</style>

<div class="content-wrapper">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <h2 class="fw-bold mb-1" style="font-size: 32px;">📑 Peminjaman Saya</h2>
                <p class="text-muted mb-0">Kelola buku yang sedang dipinjam dan cek riwayat denda.</p>
            </div>
        </div>

        <div class="row">
            @forelse($peminjaman as $p)
                @php
                    $isLate = $p->status == 'pinjam' && now()->gt($p->tgl_kembali);
                    $coverPath = $p->buku->cover;
                    $coverUrl = $coverPath 
                        ? (Str::startsWith($coverPath, 'cover-img') ? asset($coverPath) : asset('storage/'.$coverPath)) 
                        : 'https://placehold.co/110x160?text=No+Cover';
                @endphp

                <div class="col-lg-6 mb-4">
                    <div class="loan-card shadow-sm">
                        <div class="d-flex gap-4">
                            <div class="book-cover-wrapper">
                                @if($isLate)
                                    <div class="late-indicator"><i class="fas fa-clock me-1"></i> TERLAMBAT</div>
                                @endif
                                <img src="{{ $coverUrl }}" class="book-cover" alt="Cover">
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    @if($p->status == 'dikembalikan') 
                                        <span class="status-badge badge-done">Selesai</span>
                                    @elseif($p->status == 'proses_kembali') 
                                        <span class="status-badge badge-process">Verifikasi Admin</span>
                                    @elseif($p->status_denda == 'belum_bayar' || $isLate) 
                                        <span class="status-badge badge-late">Tagihan Denda</span>
                                    @elseif($p->status == 'pending') 
                                        <span class="status-badge badge-pending">Menunggu</span>
                                    @else 
                                        <span class="status-badge badge-active">Sedang Dipinjam</span>
                                    @endif
                                    <small class="text-muted fw-bold">#{{ $p->id }}</small>
                                </div>

                                <h5 class="fw-bold mb-1 text-dark">{{ Str::limit($p->buku->judul, 45) }}</h5>
                                <p class="text-muted small mb-0"><i class="fas fa-feather-alt me-1 text-primary"></i> {{ $p->buku->penulis }}</p>

                                <div class="info-grid">
                                    <div>
                                        <span class="info-label">Batas Kembali</span>
                                        <span class="info-value {{ $isLate ? 'text-danger' : '' }}">
                                            {{ \Carbon\Carbon::parse($p->tgl_kembali)->format('d M Y') }}
                                        </span>
                                    </div>
                                    <div class="text-end">
                                        <span class="info-label">Total Denda</span>
                                        <span class="info-value {{ $p->total_denda > 0 ? 'text-danger' : 'text-success' }}">
                                            {{ $p->total_denda > 0 ? 'Rp '.number_format($p->total_denda, 0, ',', '.') : 'Rp 0' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="btn-action-group">
                            <a href="{{ route('buku.detail', $p->buku->id) }}" class="btn-main btn-outline-custom">
                                <i class="fas fa-info-circle me-2"></i> Detail
                            </a>

                            @if($p->status == 'pinjam' && $p->status_denda == 'no_denda')
                                <form action="{{ route('peminjaman.proses_kembali', $p->id) }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <button type="submit" class="btn-main btn-primary-custom w-100">
                                        <i class="fas fa-undo me-2"></i> Kembalikan Buku
                                    </button>
                                </form>
                            @elseif($p->status_denda == 'belum_bayar')
                                <button type="button" class="btn-main btn-danger-custom" data-bs-toggle="modal" data-bs-target="#modalFine{{$p->id}}">
                                    <i class="fas fa-wallet me-2"></i> Bayar Denda
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                @if($p->status_denda == 'belum_bayar')
                <div class="modal fade" id="modalFine{{$p->id}}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('peminjaman.bayar_denda', $p->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body p-4 p-md-5">
                                    <div class="fine-alert mb-4">
                                        <p class="info-label mb-1">Harus Dibayar</p>
                                        <h2 class="fw-bold text-danger mb-0">Rp {{ number_format($p->total_denda, 0, ',', '.') }}</h2>
                                    </div>
                                    
                                    <div class="bank-card text-center mb-4">
                                        <p class="small text-muted mb-2 text-uppercase fw-bold" style="letter-spacing: 1px;">Metode Transfer</p>
                                        <div class="d-flex align-items-center justify-content-center gap-2 mb-1">
                                            <span class="badge bg-primary px-3 py-2">BANK BCA</span>
                                        </div>
                                        <h3 class="fw-bold text-dark mt-2 mb-1">1234567890</h3>
                                        <p class="small fw-bold text-muted mb-0">a/n Perpustakaan Digital Ginx</p>
                                    </div>
                                    
                                    <div class="mb-4 text-start">
                                        <label class="form-label small fw-bold">Bukti Transfer (Gambar/PDF)</label>
                                        <input type="file" name="bukti" class="form-control form-control-custom" required>
                                    </div>

                                    <div class="row g-2">
                                        <div class="col-5">
                                            <button type="button" class="btn btn-light w-100 py-3 rounded-4 fw-bold" data-bs-dismiss="modal">Batal</button>
                                        </div>
                                        <div class="col-7">
                                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-4 fw-bold shadow-sm">Kirim Bukti</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

            @empty
                <div class="col-12 text-center py-5">
                    <img src="https://illustrations.popsy.co/gray/reading-a-book.svg" style="width: 250px; opacity: 0.8;" class="mb-4">
                    <h4 class="fw-bold text-dark">Belum ada pinjaman</h4>
                    <p class="text-muted">Sepertinya kamu belum meminjam buku apapun.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Script Penting: Jika modal tidak muncul, pastikan ini ada di layout atau taruh sini --}}
@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection

@endsection