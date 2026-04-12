@extends('Layout.frontend.app')

@section('content')
<!-- Import Font & Icon Premium -->
<link href="https://googleapis.com" rel="stylesheet">
<link rel="stylesheet" href="https://cloudflare.com">

<style>
    :root {
        --primary: #2563eb;
        --bg-body: #f8fafc;
        --danger: #ef4444;
    }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg-body); color: #1e293b; }
    .content-wrapper { padding: 140px 0 80px; }

    /* Card Styling */
    .loan-card {
        background: #ffffff;
        border-radius: 28px;
        border: 1px solid #e2e8f0;
        padding: 24px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        margin-bottom: 25px;
        position: relative;
    }
    .loan-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        border-color: var(--primary);
    }

    .book-cover {
        width: 110px;
        height: 160px;
        object-fit: cover;
        border-radius: 18px;
        background: #f1f5f9;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
    }

    /* Badge */
    .status-badge {
        padding: 6px 14px;
        border-radius: 100px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .badge-pending { background: #fef3c7; color: #b45309; }
    .badge-active { background: #dcfce7; color: #15803d; }
    .badge-late { background: #fee2e2; color: #b91c1c; }
    .badge-process { background: #e0f2fe; color: #0369a1; }
    .badge-done { background: #f1f5f9; color: #64748b; }

    .meta-info { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px; }
    .meta-item span { display: block; font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase; margin-bottom: 4px; }
    .meta-item strong { font-size: 13px; color: #0f172a; }
    
    /* Buttons */
    .btn-group-custom { display: flex; gap: 10px; margin-top: 20px; }
    .btn-action {
        flex: 1; padding: 10px; border-radius: 14px; font-weight: 800; font-size: 12px;
        border: none; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 8px;
        text-decoration: none !important;
    }
    .btn-return { background: var(--primary); color: white !important; }
    .btn-denda { background: var(--danger); color: white !important; }
    .btn-detail { background: #f1f5f9; color: #475569 !important; border: 1px solid #e2e8f0; }

    /* Modal Styling - Memastikan Modal Tersembunyi */
    .modal { background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); }
    .modal-content { border-radius: 32px; border: none; overflow: hidden; }
</style>

<div class="content-wrapper">
    <div class="container">
        <div class="header-section mb-5">
            <h2 class="fw-bold">📑 Peminjaman Saya</h2>
            <p class="text-muted">Pantau status buku dan kelola pengembalian tepat waktu.</p>
        </div>

        <div class="row">
            @forelse($peminjaman as $p)
                @php
                    $isLate = $p->status == 'pinjam' && now()->gt($p->tgl_kembali);
                    $cover = $p->buku->cover 
                        ? (Str::startsWith($p->buku->cover, 'cover-img') ? asset($p->buku->cover) : asset('storage/'.$p->buku->cover)) 
                        : 'https://placeholder.com';
                @endphp

                <div class="col-lg-6">
                    <div class="loan-card">
                        <div class="d-flex gap-4 align-items-center">
                            <img src="{{ $cover }}" class="book-cover" alt="Cover">
                            
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    @if($p->status == 'dikembalikan') 
                                        <span class="status-badge badge-done">Selesai</span>
                                    @elseif($p->status == 'proses_kembali') 
                                        <span class="status-badge badge-process">Verifikasi Admin</span>
                                    @elseif($p->status_denda == 'belum_bayar' || $isLate) 
                                        <span class="status-badge badge-late">Terlambat</span>
                                    @elseif($p->status == 'pending') 
                                        <span class="status-badge badge-pending">Menunggu Persetujuan</span>
                                    @else 
                                        <span class="status-badge badge-active">Aktif</span>
                                    @endif
                                    <small class="text-muted fw-bold">#{{ $p->id }}</small>
                                </div>

                                <h5 class="fw-bold mb-1 text-dark">{{ Str::limit($p->buku->judul, 35) }}</h5>
                                
                                <div class="meta-info">
                                    <div class="meta-item">
                                        <span>Batas Kembali</span>
                                        <strong class="{{ $isLate ? 'text-danger' : '' }}">
                                            {{ \Carbon\Carbon::parse($p->tgl_kembali)->format('d M Y') }}
                                        </strong>
                                    </div>
                                    <div class="meta-item">
                                        <span>Total Denda</span>
                                        <strong class="{{ $p->total_denda > 0 ? 'text-danger' : '' }}">
                                            Rp {{ number_format($p->total_denda) }}
                                        </strong>
                                    </div>
                                </div>

                                <div class="btn-group-custom">
                                    <a href="{{ route('buku.detail', $p->buku->id) }}" class="btn-action btn-detail">Detail</a>

                                    {{-- Hanya muncul jika status 'pinjam' dan belum dihitung denda --}}
                                    @if($p->status == 'pinjam' && $p->status_denda == 'no_denda')
                                        <form action="{{ route('peminjaman.proses_kembali', $p->id) }}" method="POST" class="flex-grow-1">
                                            @csrf
                                            <button type="submit" class="btn-action btn-return w-100">
                                                {{ $isLate ? 'Cek Denda' : 'Kembalikan' }}
                                            </button>
                                        </form>

                                    {{-- Modal Bayar hanya muncul jika status denda sudah 'belum_bayar' --}}
                                    @elseif($p->status_denda == 'belum_bayar')
                                        <button type="button" class="btn-action btn-denda flex-grow-1" data-bs-toggle="modal" data-bs-target="#modalFine{{$p->id}}">
                                            <i class="fas fa-upload"></i> Bayar Denda
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MODAL UPLOAD (Ditempatkan di dalam loop tapi di luar loan-card) --}}
                @if($p->status_denda == 'belum_bayar')
                <div class="modal fade" id="modalFine{{$p->id}}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content shadow-lg">
                            <form action="{{ route('peminjaman.bayar_denda', $p->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body p-4 text-center">
                                    <h4 class="fw-bold mb-3">Pembayaran Denda</h4>
                                    <div class="alert alert-danger rounded-4 py-3 mb-4">
                                        <span class="d-block small fw-bold">TOTAL TAGIHAN</span>
                                        <h2 class="fw-bold mb-0 text-danger">Rp {{ number_format($p->total_denda) }}</h2>
                                    </div>
                                    <p class="small text-muted mb-4">Transfer ke <b>BCA 12345678 a/n Perpus Ginx</b></p>
                                    
                                    <div class="mb-4">
                                        <input type="file" name="bukti" class="form-control rounded-3" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 py-3 rounded-4 fw-bold">Konfirmasi Bayar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

            @empty
                <div class="col-12 text-center py-5">
                    <h5 class="text-muted fw-bold">Belum ada riwayat peminjaman.</h5>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
