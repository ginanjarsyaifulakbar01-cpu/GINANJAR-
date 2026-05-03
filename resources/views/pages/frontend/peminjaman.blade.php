@extends('Layout.frontend.app')

@section('style')
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

        .content-wrapper {
            padding: 140px 0 80px;
        }

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

        .book-cover-wrapper {
            position: relative;
            flex-shrink: 0;
        }

        .book-cover {
            width: 110px;
            height: 160px;
            object-fit: cover;
            border-radius: 18px;
            box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.15);
        }

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

        /* --- Status Badges --- */
        .status-badge {
            padding: 6px 14px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: inline-block;
        }

        .badge-pending { background: #fffbeb; color: #92400e; }
        .badge-active { background: #f0fdf4; color: #166534; }
        .badge-late { background: #fef2f2; color: #991b1b; }
        .badge-process { background: #f0f9ff; color: #075985; }
        .badge-done { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

        /* --- Info Grid --- */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            background: #f1f5f9;
            padding: 14px;
            border-radius: 20px;
            margin-top: 18px;
        }

        .info-label {
            display: block;
            font-size: 9px;
            color: #64748b;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 12px;
            color: var(--slate-900);
            font-weight: 700;
        }

        /* --- Action Buttons --- */
        .btn-action-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: auto;
            padding-top: 22px;
        }

        .btn-main {
            flex: 1;
            min-width: 120px;
            padding: 12px;
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
        .btn-success-custom { background: var(--success); color: white !important; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2); }
        
        .btn-main:hover { transform: translateY(-2px); opacity: 0.95; }

        /* --- Modal Fixes --- */
        .modal-content { border: none; border-radius: 32px; overflow: hidden; }
        .fine-alert { background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%); border: 1px dashed #fda4af; padding: 24px; border-radius: 24px; }
        .bank-card { background: #ffffff; border: 1px solid #edf2f7; border-radius: 24px; padding: 20px; }
        .form-control-custom { border-radius: 15px; padding: 12px 18px; border: 2px solid #edf2f7; background: #f8fafc; }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                @forelse($peminjaman as $p)
                    @php
                        // 1. Logika Waktu (Asia/Jakarta)
                        $tglKembali = \Carbon\Carbon::parse($p->tgl_kembali)->timezone('Asia/Jakarta')->startOfDay();
                        $sekarang = \Carbon\Carbon::now('Asia/Jakarta')->startOfDay();
                        $dendaPerHari = 2000;
                        $totalDenda = 0;

                        // 2. Hitung Denda Jika Terlambat
                        if ($p->status == 'pinjam' && $sekarang->gt($tglKembali)) {
                            $selisihDetik = $sekarang->timestamp - $tglKembali->timestamp;
                            $hariTerlambat = floor($selisihDetik / (24 * 3600));
                            if ($hariTerlambat <= 0) $hariTerlambat = 1;
                            $totalDenda = $hariTerlambat * $dendaPerHari;
                        } else {
                            $totalDenda = $p->total_denda;
                        }

                        $isLate = ($p->status == 'pinjam' && $sekarang->gt($tglKembali));

                        // 3. Logika Cover Buku
                        $coverPath = $p->buku->cover;
                        if (!$coverPath) {
                            $coverUrl = 'https://placehold.co/110x160?text=No+Cover';
                        } else {
                            $cleanPath = str_replace('storage/', '', $coverPath);
                            $cleanPath = ltrim($cleanPath, '/');
                            $coverUrl = asset('storage/' . $cleanPath);
                        }
                    @endphp

                    <div class="col-lg-6 mb-4">
                        <div class="loan-card">
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
                                    <p class="text-muted small mb-0"><i class="fas fa-feather-alt me-1 text-primary"></i>
                                        {{ $p->buku->penulis }}</p>

                                    <div class="info-grid">
                                        <div>
                                            <span class="info-label">Batas Kembali</span>
                                            <span class="info-value {{ $isLate ? 'text-danger' : '' }}">
                                                {{ $tglKembali->format('d M Y') }}
                                            </span>
                                        </div>
                                        <div class="text-end">
                                            <span class="info-label">Total Denda</span>
                                            <span class="info-value {{ $totalDenda > 0 ? 'text-danger' : 'text-success' }}">
                                                Rp {{ number_format(max(0, $totalDenda), 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="btn-action-group">
                                {{-- Tombol Detail --}}
                                <a href="{{ route('buku.detail', $p->buku->id) }}" class="btn-main btn-outline-custom">
                                    <i class="fas fa-info-circle me-2"></i> Detail
                                </a>

                                {{-- TOMBOL CETAK STRUK (YANG BARU) --}}
                                <a href="{{ route('peminjaman.cetak', $p->id) }}" target="_blank" class="btn-main btn-success-custom">
                                    <i class="fas fa-print me-2"></i> Cetak Struk
                                </a>

                                {{-- Logika Tombol Kembalikan / Bayar Denda --}}
                                @if($p->status == 'pinjam' && ($totalDenda == 0))
                                    <form action="{{ route('peminjaman.proses_kembali', $p->id) }}" method="POST" class="w-100 mt-2">
                                        @csrf
                                        <button type="submit" class="btn-main btn-primary-custom w-100">
                                            <i class="fas fa-undo me-2"></i> Kembalikan Buku
                                        </button>
                                    </form>
                                @elseif($p->status_denda == 'belum_bayar' || ($isLate && $p->status == 'pinjam'))
                                    <button type="button" class="btn-main btn-danger-custom w-100 mt-2" data-bs-toggle="modal"
                                        data-bs-target="#modalFine{{$p->id}}">
                                        <i class="fas fa-wallet me-2"></i> Bayar Denda
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Modal Bayar Denda --}}
                    @if($p->status_denda == 'belum_bayar' || $isLate)
                        <div class="modal fade" id="modalFine{{$p->id}}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('peminjaman.bayar_denda', $p->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body p-4 p-md-5 text-center">
                                            <div class="mb-4">
                                                <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    style="width: 60px; height: 60px;">
                                                    <i class="fas fa-hand-holding-dollar fa-2x"></i>
                                                </div>
                                                <h4 class="fw-bold mt-3 mb-1">Pembayaran Denda</h4>
                                            </div>

                                            <div class="fine-alert mb-4">
                                                <p class="info-label mb-1" style="color: #9b2c2c;">Total Tagihan</p>
                                                <h2 class="fw-bold text-danger mb-0">Rp {{ number_format(max(0, $totalDenda), 0, ',', '.') }}</h2>
                                            </div>

                                            <div class="bank-card mb-4 text-start">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="bg-primary px-2 py-1 rounded text-white fw-bold small">BANK BCA</div>
                                                    <div>
                                                        <h4 class="fw-bold text-dark mb-0">1234567890</h4>
                                                        <p class="small text-muted mb-0">an. Perpustakaan Digital Ginx</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-4 text-start">
                                                <label class="form-label small fw-bold text-uppercase">Bukti Transfer</label>
                                                <input type="file" name="bukti" class="form-control form-control-custom" required>
                                            </div>

                                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-4 fw-bold">Kirim Konfirmasi</button>
                                            <button type="button" class="btn btn-link text-muted mt-3 text-decoration-none" data-bs-dismiss="modal">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                @empty
                    <div class="col-12 text-center py-5">
                        <h4 class="fw-bold text-dark">Belum ada pinjaman</h4>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsecti