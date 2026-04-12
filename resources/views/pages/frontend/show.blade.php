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

    .btn-pinjam-full {
        width: 100%;
        padding: 18px;
        background: #2563eb;
        color: white !important;
        border: none;
        border-radius: 16px;
        font-size: 18px;
        font-weight: 800;
        cursor: pointer;
        transition: 0.3s;
        display: block;
        text-align: center;
        text-decoration: none;
        box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
    }
    .btn-pinjam-full:hover { background: #1d4ed8; transform: translateY(-2px); color: white; }

    @media (max-width: 992px) {
        .detail-wrapper { grid-template-columns: 1fr; }
        .detail-cover-wrapper { position: static; max-width: 300px; margin: 0 auto 30px; }
        .book-main-title { font-size: 28px; }
    }
</style>

<div class="container detail-container">

    <div class="detail-wrapper">
        <div class="detail-cover-wrapper">
            <div class="detail-img-container">
                @php
                    $imagePath = 'https://placeholder.com';
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
            {{-- ALERT BERHASIL --}}
            @if(session('success'))
                <div style="background: #d1fae5; color: #065f46; padding: 20px; border-radius: 15px; margin-bottom: 25px; font-weight: 700; border: 1px solid #10b981; display: flex; justify-content: space-between; align-items: center;">
                    <div><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
                    <a href="{{ route('riwayat.pinjam') }}" style="background: #059669; color: white; padding: 8px 16px; border-radius: 10px; font-size: 13px; text-decoration: none;">Cek Status <i class="fas fa-arrow-right"></i></a>
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
                <div class="info-item"><span class="info-label">Penulis</span><span class="info-value">{{ $buku->penulis ?? '-' }}</span></div>
                <div class="info-item"><span class="info-label">Penerbit</span><span class="info-value">{{ $buku->penerbit ?? '-' }}</span></div>
                <div class="info-item"><span class="info-label">Tahun Terbit</span><span class="info-value">{{ $buku->tahun_terbit ?? '-' }}</span></div>
                <div class="info-item"><span class="info-label">Kode Buku</span><span class="info-value">{{ $buku->id }}</span></div>
            </div>

            <div class="description-box">
                <h3>Sinopsis / Deskripsi</h3>
                <p>{{ $buku->deskripsi ?? 'Tidak ada deskripsi untuk buku ini.' }}</p>
            </div>

            {{-- LOGIKA STATUS PINJAM --}}
            @php
                $statusPinjam = \App\Models\Peminjaman::where('user_id', Auth::id())
                    ->where('buku_id', $buku->id)
                    ->whereIn('status', ['pending', 'pinjam', 'proses_kembali'])
                    ->first();

                $isTerlambat = false;
                if ($statusPinjam && $statusPinjam->status == 'pinjam' && \Carbon\Carbon::now()->gt($statusPinjam->tgl_kembali)) {
                    $isTerlambat = true;
                }
            @endphp

            @if($statusPinjam)
                <div style="background: {{ $isTerlambat ? '#fee2e2' : '#eff6ff' }}; 
                            border: 2px solid {{ $isTerlambat ? '#ef4444' : '#2563eb' }}; 
                            padding: 30px; border-radius: 24px; text-align: center;">
                    <div style="font-size: 18px; font-weight: 800; color: {{ $isTerlambat ? '#b91c1c' : '#1e40af' }}; margin-bottom: 10px;">
                        <i class="fas {{ $isTerlambat ? 'fa-clock' : 'fa-info-circle' }}"></i> 
                        {{ $isTerlambat ? 'Peringatan: Peminjaman Terlambat!' : 'Anda sedang memproses buku ini' }}
                    </div>
                    <p style="color: {{ $isTerlambat ? '#991b1b' : '#64748b' }}; margin-bottom: 20px;">
                        Status: <strong>{{ $isTerlambat ? 'TERLAMBAT' : strtoupper($statusPinjam->status) }}</strong>
                    </p>
                    <a href="{{ route('riwayat.pinjam') }}" class="btn-pinjam-full" style="background: {{ $isTerlambat ? '#ef4444' : '#2563eb' }};">
                        {{ $isTerlambat ? 'Selesaikan Denda Sekarang' : 'Lihat Detail Peminjaman' }}
                    </a>
                </div>
            @elseif($buku->stok > 0)
                <form action="{{ route('peminjaman.ajukan', $buku->id) }}" method="POST">
                    @csrf
                    <div class="form-peminjaman-box">
                        <div style="margin-bottom: 20px;">
                            <label class="info-label" style="color: #0f172a; font-size: 15px;"><i class="fas fa-calendar-day text-primary"></i> Rencana Tanggal Pinjam</label>
                            <input type="date" name="tgl_pinjam" class="input-custom" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div>
                            <label class="info-label" style="color: #0f172a; font-size: 15px;"><i class="fas fa-hourglass-half text-primary"></i> Durasi Peminjaman</label>
                            <div style="display: flex; align-items: center; gap: 15px; margin-top: 5px;">
                                <div style="flex: 1;"><input type="number" class="input-custom" value="7" readonly></div>
                                <div style="font-weight: 800; color: #64748b; padding-top: 8px;">Hari</div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn-pinjam-full"><i class="fas fa-book-reader"></i> Ajukan Peminjaman Sekarang</button>
                </form>
            @else
                <div style="background: #f1f5f9; color: #64748b; padding: 25px; border-radius: 20px; text-align: center; font-weight: 700; border: 1px solid #e2e8f0;">
                    <i class="fas fa-info-circle"></i> Maaf, saat ini buku tidak tersedia untuk dipinjam.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
