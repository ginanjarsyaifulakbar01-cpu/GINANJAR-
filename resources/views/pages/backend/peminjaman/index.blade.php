@extends('Layout.backend.app')

@section('content')
<!-- Import Font & Icon Premium -->
<link href="https://googleapis.com" rel="stylesheet">
<link rel="stylesheet" href="https://cloudflare.com">

<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f4f7fe; }
    .card-admin { border-radius: 24px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; }
    
    .table thead th { 
        background: #f8fafc; 
        text-transform: uppercase; 
        font-size: 11px; 
        font-weight: 800; 
        color: #64748b; 
        padding: 20px;
    }
    
    .table tbody td { padding: 20px; vertical-align: middle; }
    
    /* Bukti Denda Preview */
    .bukti-container {
        position: relative;
        width: 60px;
        height: 60px;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        cursor: pointer;
        transition: 0.3s;
    }
    .bukti-container:hover { transform: scale(1.1); box-shadow: 0 8px 15px rgba(0,0,0,0.15); }
    .bukti-img-preview { width: 100%; height: 100%; object-fit: cover; }
    
    /* Badge Custom */
    .badge-status { padding: 8px 14px; border-radius: 10px; font-weight: 700; font-size: 11px; display: inline-block; }
    .bg-pending { background: #fff7ed; color: #c2410c; }
    .bg-pinjam { background: #eff6ff; color: #1d4ed8; }
    .bg-proses { background: #f0fdf4; color: #15803d; }
    .bg-late { background: #fee2e2; color: #b91c1c; }

    .btn-verify { border-radius: 12px; padding: 10px 18px; font-size: 12px; font-weight: 800; border: none; transition: 0.3s; }
    .btn-approve { background: #10b981; color: white; }
    .btn-approve:hover { background: #059669; }
    .btn-reject { background: #ef4444; color: white; }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-800 text-dark">Manajemen Transaksi</h3>
            <p class="text-muted">Verifikasi bukti denda dan persetujuan peminjaman buku.</p>
        </div>
    </div>

    <div class="card card-admin">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Peminjam & Buku</th>
                            <th>Jadwal</th>
                            <th>Status & Denda</th>
                            <th>Bukti Pembayaran</th>
                            <th class="text-center">Aksi Konfirmasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $p)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width: 40px; height: 40px; background: #e2e8f0; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #475569;">
                                        {{ substr($p->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $p->user->name }}</div>
                                        <div class="small text-primary fw-bold">{{ Str::limit($p->buku->judul, 30) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-bold text-muted">Pinjam: {{ \Carbon\Carbon::parse($p->tgl_pinjam)->format('d/m/y') }}</div>
                                <div class="small fw-bold text-danger">Batas: {{ \Carbon\Carbon::parse($p->tgl_kembali)->format('d/m/y') }}</div>
                            </td>
                            <td>
                                @if($p->status == 'pending')
                                    <span class="badge-status bg-pending">PENGAJUAN</span>
                                @elseif($p->status == 'pinjam')
                                    <span class="badge-status bg-pinjam">DIPINJAM</span>
                                @elseif($p->status == 'proses_kembali')
                                    <span class="badge-status bg-proses">PROSES BALIK</span>
                                @else
                                    <span class="badge-status bg-light text-muted">SELESAI</span>
                                @endif
                                
                                @if($p->total_denda > 0)
                                    <div class="mt-1 small fw-bold text-danger">Tagihan: Rp{{ number_format($p->total_denda) }}</div>
                                @endif
                            </td>
                            <td>
                                @if($p->bukti_bayar)
                                    <div class="bukti-container" data-bs-toggle="modal" data-bs-target="#modalBukti{{ $p->id }}">
                                        <img src="{{ asset('storage/'.$p->bukti_bayar) }}" class="bukti-img-preview">
                                        <div style="position: absolute; bottom: 0; background: rgba(0,0,0,0.5); width: 100%; color: white; font-size: 8px; text-align: center;">ZOOM</div>
                                    </div>

                                    <!-- MODAL ZOOM FOTO -->
                                    <div class="modal fade" id="modalBukti{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content bg-transparent border-0">
                                                <div class="modal-body p-0 text-center">
                                                    <img src="{{ asset('storage/'.$p->bukti_bayar) }}" class="img-fluid rounded-4 shadow-lg">
                                                    <button type="button" class="btn btn-light mt-3 fw-bold rounded-pill px-4" data-bs-dismiss="modal">Tutup Preview</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small italic">Tidak ada bukti</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($p->status == 'pending')
                                    <form action="{{ route('peminjaman.review', $p->id) }}" method="POST" class="d-flex gap-2 justify-content-center">
                                        @csrf
                                        <button name="action" value="setuju" class="btn-verify btn-approve">SETUJU</button>
                                        <button name="action" value="tolak" class="btn-verify btn-reject">TOLAK</button>
                                    </form>
                                @elseif($p->status == 'proses_kembali')
                                    <form action="{{ route('peminjaman.review_kembali', $p->id) }}" method="POST" class="d-flex gap-2 justify-content-center">
                                        @csrf
                                        <button name="action" value="setuju" class="btn-verify btn-approve">TERIMA BUKU</button>
                                        @if($p->status_denda == 'pending_admin')
                                            <button name="action" value="tolak_denda" class="btn-verify btn-reject" title="Bukti Salah">TOLAK BUKTI</button>
                                        @endif
                                    </form>
                                @else
                                    <i class="fas fa-check-circle text-success shadow-sm p-2 rounded-circle"></i>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted fw-bold">Belum ada transaksi masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-4">{{ $data->links() }}</div>
</div>
@endsection
