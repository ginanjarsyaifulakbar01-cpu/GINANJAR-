@extends('Layout.frontend.app')

@section('content')
<style>
    /* 1. LAYOUT & TYPOGRAPHY */
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
    body { background-color: #f8fafc; color: #1e293b; font-family: 'Plus Jakarta Sans', sans-serif; }
    .detail-container { padding-top: 140px; padding-bottom: 80px; min-height: 100vh; }
    .detail-wrapper {
        display: grid; grid-template-columns: 350px 1fr; gap: 50px;
        background: white; padding: 40px; border-radius: 32px;
        border: 1px solid #e2e8f0; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.03);
    }

    /* 2. COVER & BUKTI BAYAR */
    .detail-img-container {
        width: 100%; aspect-ratio: 3/4; background: #f1f5f9;
        border-radius: 24px; overflow: hidden; border: 1px solid #e2e8f0;
        display: flex; align-items: center; justify-content: center;
    }
    .detail-img { width: 100%; height: 100%; object-fit: cover; }

    /* 3. STATUS BADGES */
    .status-badge { display: inline-block; padding: 8px 20px; border-radius: 12px; font-weight: 800; font-size: 13px; text-transform: uppercase; margin-bottom: 15px; }
    .status-pinjam { background: #d1fae5; color: #065f46; }
    .status-telat { background: #fee2e2; color: #991b1b; }
    .status-proses_kembali { background: #e0e7ff; color: #4338ca; }
    .status-dikembalikan { background: #f1f5f9; color: #475569; }
    .status-pending { background: #fef3c7; color: #92400e; }

    /* 4. INFO GRID */
    .transaction-title { font-size: 32px; font-weight: 800; color: #0f172a; margin-bottom: 5px; }
    .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-top: 30px; padding: 25px; background: #f8fafc; border-radius: 24px; border: 1px solid #f1f5f9; }
    .info-item { display: flex; flex-direction: column; gap: 4px; }
    .info-label { font-size: 11px; color: #94a3b8; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
    .info-value { font-size: 16px; color: #0f172a; font-weight: 700; }

    /* 5. BUTTONS */
    .btn-action { width: 100%; padding: 16px; border-radius: 18px; border: none; font-weight: 800; font-size: 15px; cursor: pointer; transition: 0.3s; text-align: center; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 10px; }
    .btn-primary-custom { background: #2563eb; color: white; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2); }
    .btn-primary-custom:hover { background: #1d4ed8; transform: translateY(-2px); }
    .btn-danger-custom { background: #ef4444; color: white; }
    .btn-light-custom { background: #f1f5f9; color: #475569; margin-top: 15px; }

    /* 6. DENDA BOX */
    .denda-box { margin-top: 30px; padding: 25px; border-radius: 24px; background: #fff1f2; border: 1px solid #fecdd3; }
</style>

<div class="container detail-container">
    <nav style="margin-bottom: 30px;">
        <a href="{{ route('riwayat.pinjam') }}" style="text-decoration: none; color: #64748b; font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
        </a>
    </nav>

    <div class="detail-wrapper">
        {{-- SISI KIRI: COVER & BUKTI --}}
        <div class="detail-cover-wrapper">
            <div class="detail-img-container">
                @php
                    $imagePath = $pinjam->buku->cover ? (Str::startsWith($pinjam->buku->cover, 'cover-img') ? asset($pinjam->buku->cover) : asset('storage/' . $pinjam->buku->cover)) : 'https://via.placeholder.com/400x600?text=No+Cover';
                @endphp
                <img src="{{ $imagePath }}" class="detail-img" alt="{{ $pinjam->buku->judul }}">
            </div>
            
            @if($pinjam->bukti_bayar)
            <div style="margin-top: 25px;">
                <label class="info-label" style="text-align: center; display: block; margin-bottom: 10px;">Bukti Pembayaran Denda</label>
                <a href="{{ asset('storage/' . $pinjam->bukti_bayar) }}" target="_blank">
                    <img src="{{ asset('storage/' . $pinjam->bukti_bayar) }}" style="width: 100%; border-radius: 18px; border: 2px solid #e2e8f0; cursor: zoom-in;">
                </a>
            </div>
            @endif
        </div>

        {{-- SISI KANAN: DETAIL --}}
        <div class="detail-content">
            @php
                $tglKembali = \Carbon\Carbon::parse($pinjam->tgl_kembali);
                $tglSekarang = \Carbon\Carbon::now();
                // Status telat jika hari ini sudah melewati tanggal kembali dan belum dikembalikan/pending
                $isLate = ($tglSekarang->gt($tglKembali) && !in_array($pinjam->status, ['dikembalikan', 'pending']));
                
                // Hitung nominal denda (Rp 5.000 / hari)
                $nominalDenda = 0;
                if ($tglSekarang->gt($tglKembali) && $pinjam->status != 'pending') {
                    $selisih_hari = $tglSekarang->diffInDays($tglKembali);
                    $nominalDenda = $selisih_hari * 5000;
                }
                
                // Jika sudah ada total_denda di database, gunakan itu (fixed denda)
                if($pinjam->total_denda > 0) {
                    $nominalDenda = $pinjam->total_denda;
                }
            @endphp

            <div class="d-flex gap-2 align-items-center mb-2">
                @if($pinjam->status == 'dikembalikan')
                    <span class="status-badge status-dikembalikan">Selesai</span>
                @elseif($pinjam->status == 'proses_kembali')
                    <span class="status-badge status-proses_kembali">Proses Verifikasi</span>
                @elseif($pinjam->status == 'pending')
                    <span class="status-badge status-pending">Menunggu Persetujuan</span>
                @elseif($isLate)
                    <span class="status-badge status-telat">Terlambat {{ $tglSekarang->diffInDays($tglKembali) }} Hari</span>
                @else
                    <span class="status-badge status-pinjam">Aktif Dipinjam</span>
                @endif

                @if($nominalDenda > 0)
                    <span class="status-badge bg-danger text-white">Total Denda: Rp{{ number_format($nominalDenda, 0, ',', '.') }}</span>
                @endif
            </div>
            
            <h1 class="transaction-title">{{ $pinjam->buku->judul }}</h1>
            <p style="color: #64748b; font-weight: 600;">ID Transaksi: #TRX-{{ str_pad($pinjam->id, 5, '0', STR_PAD_LEFT) }}</p>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Tanggal Pinjam</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($pinjam->tgl_pinjam)->format('d F Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Jatuh Tempo</span>
                    <span class="info-value {{ $isLate ? 'text-danger' : '' }}">
                        {{ $tglKembali->format('d F Y') }}
                    </span>
                </div>
                @if($pinjam->tgl_realisasi_kembali)
                <div class="info-item">
                    <span class="info-label">Dikembalikan Pada</span>
                    <span class="info-value text-success">{{ \Carbon\Carbon::parse($pinjam->tgl_realisasi_kembali)->format('d F Y') }}</span>
                </div>
                @endif
                <div class="info-item">
                    <span class="info-label">Status Denda</span>
                    <span class="info-value">
                        @if($nominalDenda > 0)
                            @if($pinjam->status_denda == 'lunas')
                                <span class="text-success"><i class="fas fa-check-circle"></i> LUNAS</span>
                            @elseif($pinjam->status_denda == 'pending_admin')
                                <span class="text-primary"><i class="fas fa-clock"></i> MENUNGGU VERIFIKASI</span>
                            @else
                                <span class="text-danger"><i class="fas fa-exclamation-circle"></i> BELUM BAYAR</span>
                            @endif
                        @else
                            <span class="text-muted">TIDAK ADA DENDA</span>
                        @endif
                    </span>
                </div>
            </div>

            {{-- ACTION: TOMBOL KEMBALIKAN --}}
            @if($pinjam->status == 'pinjam')
                <div style="margin-top: 40px;">
                    <form action="{{ route('peminjaman.proses_kembali', $pinjam->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-action btn-primary-custom">
                            <i class="fas fa-undo-alt"></i> Kembalikan Buku Sekarang
                        </button>
                    </form>
                    <p class="text-muted small mt-2 text-center">*Klik tombol ini saat Anda sudah mengembalikan buku ke perpustakaan.</p>
                </div>
            @endif

            {{-- BOX PEMBAYARAN DENDA --}}
            @if($nominalDenda > 0 && $pinjam->status_denda != 'lunas')
                <div class="denda-box">
                    <h5 style="font-weight: 800; color: #991b1b; margin-bottom: 10px;">
                        <i class="fas fa-exclamation-triangle"></i> Instruksi Pembayaran Denda
                    </h5>
                    <p style="font-size: 14px; color: #475569; margin-bottom: 20px;">
                        Anda terlambat mengembalikan buku. Silakan transfer denda sebesar <b>Rp{{ number_format($nominalDenda, 0, ',', '.') }}</b>.
                    </p>
                    <div style="background: white; padding: 15px; border-radius: 15px; margin-bottom: 20px; border: 1px solid #fecdd3;">
                        <span class="info-label">Bank BCA</span>
                        <span class="info-value" style="display: block;">8829-01-2231-00</span>
                        <span class="info-label mt-2">Atas Nama</span>
                        <span class="info-value" style="display: block;">Perpustakaan Digital</span>
                    </div>
                    
                    @if($pinjam->status_denda == 'pending_admin')
                        <div style="background: #eff6ff; padding: 15px; border-radius: 15px; text-align: center;">
                            <i class="fas fa-spinner fa-spin text-primary mb-2"></i>
                            <p class="text-primary fw-bold mb-0">Bukti Sedang Diverifikasi Admin</p>
                        </div>
                    @else
                        <form action="{{ route('peminjaman.bayar_denda', $pinjam->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label class="info-label" style="margin-bottom: 8px; display: block;">Upload Bukti Transfer</label>
                                <input type="file" name="bukti" class="form-control shadow-sm" style="border-radius: 12px;" required>
                            </div>
                            <button type="submit" class="btn-action btn-danger-custom">
                                <i class="fas fa-upload"></i> Kirim Bukti Pembayaran
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            <div style="margin-top: 20px;">
                <a href="{{ route('katalog') }}" class="btn-action btn-light-custom">
                    <i class="fas fa-search"></i> Telusuri Buku Lain
                </a>
            </div>
        </div>
    </div>
</div>
@endsection