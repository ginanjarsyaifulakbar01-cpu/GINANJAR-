@extends('Layout.backend.app')

@section('content')
<style>
    .page-content { background: #f8fafc; border-radius: 15px; padding: 20px; font-family: 'Plus Jakarta Sans', sans-serif; }
    .table-modern { width: 100%; border-collapse: separate; border-spacing: 0 10px; }
    .table-modern th { font-size: 0.7rem; text-transform: uppercase; color: #94a3b8; padding: 0 20px 10px; }
    .table-modern td { background: #fff; padding: 18px 20px; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .table-modern tr td:first-child { border-radius: 12px 0 0 12px; }
    .table-modern tr td:last-child { border-radius: 0 12px 12px 0; }
    
    .badge-status { padding: 6px 12px; border-radius: 999px; font-size: 0.7rem; font-weight: 600; }
    .pending { background: #fff7ed; color: #c2410c; }
    .pinjam { background: #eef2ff; color: #4338ca; }
    .review { background: #e0f2fe; color: #0369a1; }
    .kembali { background: #ecfdf5; color: #15803d; }

    .btn-verif { background: #6366f1; color: white; border-radius: 10px; padding: 8px 16px; font-size: 0.8rem; font-weight: 700; border: none; transition: 0.3s; text-decoration: none; cursor: pointer; }
    .btn-verif:hover { background: #4f46e5; transform: translateY(-2px); color: white; }
    .modal-content { border-radius: 20px; border: none; }
    .img-bukti { width: 100%; max-height: 250px; object-fit: contain; border-radius: 12px; border: 2px dashed #e2e8f0; }
</style>

@if(session('success'))
    <div class="alert alert-success mx-3 rounded-4 border-0 shadow-sm">{{ session('success') }}</div>
@endif

<div class="page-content">
    <div class="table-responsive" style="overflow: visible;">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Buku</th>
                    <th>Peminjam</th>
                    <th>Jadwal & Denda</th>
                    <th>Status Pinjam</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $p)
                <tr>
                    <td>
                        <div class="fw-bold text-dark">{{ $p->buku->judul }}</div>
                        <small class="text-muted">ID: #TRX-{{ str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</small>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $p->user->name }}</div>
                        <small class="text-muted" style="font-size: 0.7rem;">{{ $p->user->email }}</small>
                    </td>
                    <td>
                        <div style="font-size: 0.8rem;">
                            <span class="text-muted">Batas:</span> 
                            <span class="fw-bold text-dark">{{ $p->tgl_kembali ? \Carbon\Carbon::parse($p->tgl_kembali)->format('d/m/Y') : '-' }}</span>
                            
                            @php
                                $tglKembali = \Carbon\Carbon::parse($p->tgl_kembali);
                                $tglSekarang = \Carbon\Carbon::now();
                                $dendaOtomatis = 0;
                                
                                // Jika status belum dikembalikan dan sudah lewat tanggal kembali
                                if ($p->status != 'dikembalikan' && $tglSekarang->gt($tglKembali)) {
                                    $hari = $tglSekarang->diffInDays($tglKembali);
                                    $dendaOtomatis = $hari * 5000;
                                }
                                
                                // Gunakan denda tertinggi antara hitungan sistem atau yang sudah tercatat di DB
                                $totalDenda = max($dendaOtomatis, $p->total_denda);
                            @endphp

                            <div class="mt-1">
                                @if($totalDenda > 0)
                                    <span class="badge bg-danger" style="font-size: 0.65rem;">
                                        Denda: Rp {{ number_format($totalDenda, 0, ',', '.') }}
                                    </span>
                                @else
                                    <small class="text-muted italic">Tanpa Denda</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($p->status == 'pending') <span class="badge-status pending">Menunggu Approval</span>
                        @elseif($p->status == 'pinjam') <span class="badge-status pinjam">Aktif Dipinjam</span>
                        @elseif($p->status == 'proses_kembali') <span class="badge-status review">Review Pengembalian</span>
                        @elseif($p->status == 'dikembalikan') <span class="badge-status kembali">Selesai</span>
                        @endif
                    </td>
                    <td class="text-center">
                        {{-- STATUS PENDING --}}
                        @if($p->status == 'pending')
                            <div class="d-flex gap-2 justify-content-center">
                                <form action="{{ route('peminjaman.review', $p->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" name="action" value="setuju" class="btn btn-sm btn-success rounded-3 px-3">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                <form action="{{ route('peminjaman.review', $p->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" name="action" value="tolak" class="btn btn-sm btn-danger rounded-3 px-3">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>

                        {{-- STATUS PROSES KEMBALI --}}
                        @elseif($p->status == 'proses_kembali')
                            <button type="button" class="btn-verif" data-bs-toggle="modal" data-bs-target="#modalVerif{{ $p->id }}">
                                <i class="bi bi-shield-check me-1"></i> Verif Kembali
                            </button>

                            <div class="modal fade" id="modalVerif{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content shadow-lg text-start">
                                        <form action="{{ route('peminjaman.review_kembali', $p->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body p-4">
                                                <h5 class="fw-bold mb-3 text-center">Konfirmasi Pengembalian</h5>
                                                <div class="p-3 bg-light rounded-4 mb-3">
                                                    <small class="text-muted d-block text-start">Peminjam:</small>
                                                    <span class="fw-bold d-block text-start">{{ $p->user->name }}</span>
                                                    <small class="text-muted d-block mt-2 text-start">Buku:</small>
                                                    <span class="fw-bold d-block text-start">{{ $p->buku->judul }}</span>
                                                </div>

                                                @if($totalDenda > 0)
                                                    <div class="text-center mb-3">
                                                        <small class="fw-bold d-block mb-2">Status Denda:</small>
                                                        @if($p->bukti_bayar)
                                                            <a href="{{ asset('storage/' . $p->bukti_bayar) }}" target="_blank">
                                                                <img src="{{ asset('storage/' . $p->bukti_bayar) }}" class="img-bukti" alt="Bukti">
                                                            </a>
                                                            <span class="badge bg-success d-block mt-2">Bukti Pembayaran Tersedia</span>
                                                        @else
                                                            <div class="alert alert-warning py-2 mb-0" style="font-size: 0.8rem;">
                                                                <i class="bi bi-exclamation-triangle-fill me-1"></i> User belum upload bukti bayar denda.
                                                            </div>
                                                        @endif
                                                        <span class="badge bg-danger d-block mt-2">Rp {{ number_format($totalDenda, 0, ',', '.') }}</span>
                                                    </div>
                                                @endif
                                                <p class="text-center mt-3 mb-0">Apakah buku sudah diterima kembali dengan baik?</p>
                                            </div>
                                            <div class="modal-footer border-0 pb-4 justify-content-center">
                                                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" name="action" value="setuju" class="btn btn-success px-4 fw-bold shadow-sm">Selesaikan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        {{-- STATUS SELESAI --}}
                        @elseif($p->status == 'dikembalikan')
                            <span class="text-success fw-bold"><i class="bi bi-check-circle-fill"></i> Selesai</span>
                        
                        {{-- SEDANG DIPINJAM --}}
                        @else
                            <small class="text-muted italic">Menunggu Pengembalian</small>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-5">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection