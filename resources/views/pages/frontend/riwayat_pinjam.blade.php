@extends('Layout.frontend.app')

@section('content')
<style>
    /* 1. Global Reset & Soft UI */
    body { font-family: 'Poppins', sans-serif; background-color: #f1f5f9; color: #334155; }
    .main-wrapper { padding-top: 130px; padding-bottom: 100px; min-height: 100vh; }

    /* 2. Glassmorphism Navigation */
    .nav-container {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        padding: 8px;
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        display: inline-flex;
    }
    .custom-pill .nav-link {
        border-radius: 15px;
        padding: 12px 28px;
        color: #64748b;
        font-weight: 600;
        font-size: 14px;
        border: none;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .custom-pill .nav-link.active {
        background: #6366f1; /* Warna Indigo biar match sama GinxAdmin */
        color: white;
        box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
    }

    /* 3. Modern Loan Cards */
    .card-item {
        background: white;
        border-radius: 28px;
        border: 1px solid #e2e8f0;
        padding: 30px;
        margin-bottom: 25px;
        position: relative;
        transition: all 0.3s ease;
    }
    .card-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.05);
        border-color: #6366f1;
    }

    .book-icon-wrapper {
        width: 70px; height: 90px;
        background: linear-gradient(135deg, #e0e7ff 0%, #eef2ff 100%);
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        color: #6366f1; font-size: 28px;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }

    /* 4. Denda & Status Branding */
    .fine-card {
        background: #fff1f2;
        border: 1px solid #ffe4e6;
        padding: 15px 25px;
        border-radius: 18px;
        display: flex; align-items: center;
        color: #e11d48;
    }
    .fine-amount { font-size: 18px; font-weight: 800; display: block; }
    
    .status-badge-pro {
        padding: 8px 18px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
    }
    .status-approved { background: #dcfce7; color: #15803d; }
    .status-pending { background: #fef9c3; color: #a16207; }

    /* 5. Minimalist History Table */
    .history-container {
        background: white;
        border-radius: 30px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
    }
    .table-pro thead th {
        background: #fafafa;
        padding: 25px;
        font-size: 12px;
        color: #94a3b8;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-pro tbody td { padding: 25px; border-bottom: 1px solid #f8fafc; }

</style>

<div class="container main-wrapper">
    <div class="row mb-5">
        <div class="col-md-7">
            <h2 class="font-weight-bold text-dark mb-2" style="font-size: 2.2rem; letter-spacing: -1px;">
                Aktivitas Membaca 📖
            </h2>
            <p class="text-muted" style="font-size: 1.1rem;">Pantau status buku yang ente pinjam secara real-time.</p>
        </div>
        <div class="col-md-5 text-md-right align-self-end">
            <div class="nav-container">
                <ul class="nav nav-pills custom-pill" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-aktif-tab" data-toggle="pill" href="#pills-aktif" role="tab">Sedang Pinjam</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-riwayat-tab" data-toggle="pill" href="#pills-riwayat" role="tab">Riwayat</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-aktif" role="tabpanel">
            <div class="row">
                <div class="col-12">
                    @forelse($sedangDipinjam as $s)
                        @php
                            $hariIni = \Carbon\Carbon::now();
                            $deadline = \Carbon\Carbon::parse($s->tgl_kembali);
                            $isOverdue = $hariIni->gt($deadline);
                            $daysDiff = $isOverdue ? $hariIni->diffInDays($deadline) : 0;
                            $fine = $daysDiff * 5000;
                        @endphp
                        
                        <div class="card-item shadow-sm border-0">
                            <div class="row align-items-center">
                                <div class="col-lg-4 col-md-6 d-flex align-items-center mb-3 mb-md-0">
                                    <div class="book-icon-wrapper mr-4">
                                        <i class="fas fa-book-reader"></i>
                                    </div>
                                    <div>
                                        <span class="status-badge-pro status-approved mb-2 d-inline-block">Approved</span>
                                        <h4 class="font-weight-bold mb-0 text-dark" style="font-size: 1.25rem;">{{ $s->buku->judul }}</h4>
                                        <small class="text-muted">Mulai: {{ \Carbon\Carbon::parse($s->tgl_pinjam)->format('d/m/Y') }}</small>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-3 text-md-center mb-3 mb-md-0">
                                    <div style="background: #f8fafc; padding: 10px; border-radius: 15px;">
                                        <small class="text-uppercase font-weight-bold text-muted" style="font-size: 10px; letter-spacing: 1px;">Jatuh Tempo</small>
                                        <div class="font-weight-bold {{ $isOverdue ? 'text-danger' : 'text-primary' }}" style="font-size: 1.1rem;">
                                            {{ $deadline->format('d M Y') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-5 col-md-3">
                                    @if($isOverdue)
                                        <div class="fine-card">
                                            <i class="fas fa-hourglass-end fa-2x mr-3 opacity-50"></i>
                                            <div>
                                                <small class="font-weight-bold text-uppercase" style="font-size: 10px;">Terlambat {{ $daysDiff }} Hari</small>
                                                <span class="fine-amount">Rp{{ number_format($fine, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-md-right">
                                            <div class="d-inline-flex align-items-center px-4 py-2" style="background: #f0fdf4; border-radius: 12px; border: 1px solid #bbf7d0;">
                                                <i class="fas fa-check-circle text-success mr-2"></i>
                                                <span class="text-success font-weight-600">Aman dari denda</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 card-item border-0 shadow-sm">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="100" class="mb-3 opacity-25">
                            <h5 class="text-muted">Belum ada buku yang lagi dipinjam, Ngap.</h5>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="pills-riwayat" role="tabpanel">
            <div class="history-container shadow-sm border-0">
                <div class="table-responsive">
                    <table class="table table-pro mb-0">
                        <thead>
                            <tr>
                                <th>Judul Buku</th>
                                <th>Waktu Pengajuan</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayatLengkap as $r)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded p-2 mr-3"><i class="fas fa-history text-muted"></i></div>
                                            <div>
                                                <div class="font-weight-bold text-dark">{{ $r->buku->judul }}</div>
                                                <small class="text-muted">Ref ID: #{{ $r->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-muted small">
                                        {{ \Carbon\Carbon::parse($r->created_at)->format('d M Y, H:i') }}
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $badgeClass = [
                                                'pending' => 'status-pending',
                                                'dikembalikan' => 'status-approved',
                                                'ditolak' => 'bg-soft-danger text-danger'
                                            ][$r->status] ?? 'bg-light';
                                        @endphp
                                        <span class="status-badge-pro {{ $badgeClass }}">{{ $r->status }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center py-5 text-muted font-italic">Belum ada jejak riwayat peminjaman.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection