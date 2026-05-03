@extends('Layout.backend.app')

@section('content')
    <link href="https://fonts.googleapis.com" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f4f7fe;
        }

        .card-admin {
            border-radius: 24px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table thead th {
            background: #f8fafc;
            text-transform: uppercase;
            font-size: 11px;
            font-weight: 800;
            color: #64748b;
            padding: 20px;
        }

        .table tbody td {
            padding: 20px;
            vertical-align: middle;
        }

        /* Bukti Denda Preview */
        .bukti-container {
            position: relative;
            width: 60px;
            height: 60px;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: 0.3s;
        }

        .bukti-container:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
        }

        .bukti-img-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Badge Custom */
        .badge-status {
            padding: 8px 14px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 11px;
            display: inline-block;
            text-transform: uppercase;
        }

        .bg-pending { background: #fff7ed; color: #c2410c; }
        .bg-pinjam { background: #eff6ff; color: #1d4ed8; }
        .bg-proses { background: #f0fdf4; color: #15803d; }
        .bg-late { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
        .bg-selesai { background: #f1f5f9; color: #475569; }

        .btn-verify {
            border-radius: 12px;
            padding: 10px 18px;
            font-size: 12px;
            font-weight: 800;
            border: none;
            transition: 0.3s;
        }

        .btn-approve { background: #10b981; color: white; }
        .btn-approve:hover { background: #059669; }
        .btn-reject { background: #ef4444; color: white; }

    </style>

    <div class="container-fluid py-4">
        <div class="card card-admin">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Peminjam & Buku</th>
                                <th>Jadwal</th>
                                <th class="text-center">Status & Denda</th>
                                <th class="text-center">Bukti Pembayaran</th>
                                <th class="text-center">Aksi Konfirmasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data as $p)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-start gap-3">
                                            {{-- Book Cover --}}
                                            <div style="width: 40px; height: 40px; background: #e2e8f0; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #475569;">
                                                {{ substr($p->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                    </td>
                                    <td>
                                        <div class="small fw-bold text-muted">Pinjam: {{ $p->tgl_pinjam->format('d/m/y') }}</div>
                                        <div class="small fw-bold text-danger">Batas: {{ $p->tgl_kembali->format('d/m/y') }}</div>
                                    </td>
                                    <td class="text-center">
                                        {{-- LOGIKA STATUS & NOTIFIKASI TERLAMBAT --}}
                                        @if($p->status == 'pinjam' && $p->is_late)
                                            <span class="badge-status bg-late">
                                                <i class="fas fa-clock me-1"></i> TERLAMBAT
                                            </span>
                                            <div class="mt-1 small fw-bold text-danger">
                                                Denda Live: Rp {{ number_format($p->denda, 0, ',', '.') }}
                                            </div>
                                        @elseif($p->status == 'pending')
                                            <span class="badge-status bg-pending">PENGAJUAN</span>
                                        @elseif($p->status == 'pinjam')
                                            <span class="badge-status bg-pinjam">DIPINJAM</span>
                                        @elseif($p->status == 'proses_kembali')
                                            <span class="badge-status bg-proses">PROSES BALIK</span>
                                        @else
                                            <span class="badge-status bg-selesai">SELESAI</span>
                                        @endif

                                        {{-- Tampilkan Status Denda Jika Sudah Ada Tagihan --}}
                                        @if($p->total_denda > 0 && $p->status == 'dikembalikan')
                                            <div class="mt-1">
                                                <span class="badge bg-success rounded-pill" style="font-size: 9px;">LUNAS: Rp {{ number_format($p->total_denda) }}</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($p->bukti_bayar)
                                            <div class="d-flex justify-content-center">
                                                <div class="bukti-container" data-bs-toggle="modal" data-bs-target="#modalBukti{{ $p->id }}">
                                                    <img src="{{ asset('storage/' . $p->bukti_bayar) }}" class="bukti-img-preview">
                                                </div>
                                            </div>

                                            <div class="modal fade" id="modalBukti{{ $p->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content bg-transparent border-0">
                                                        <div class="modal-body p-0 text-center">
                                                            <img src="{{ asset('storage/' . $p->bukti_bayar) }}" class="img-fluid rounded-4 shadow-lg">
                                                            <div class="mt-3">
                                                                <button type="button" class="btn btn-light fw-bold rounded-pill px-4" data-bs-dismiss="modal">Tutup Preview</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted small"><em>Tidak ada bukti</em></span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($p->status == 'pending')
                                            <form action="{{ route('peminjaman.review', $p->id) }}" method="POST" class="d-flex gap-2 justify-content-center">
                                                @csrf
                                                <button name="action" value="setuju" class="btn-verify btn-approve shadow-sm">SETUJU</button>
                                                <button name="action" value="tolak" class="btn-verify btn-reject shadow-sm">TOLAK</button>
                                            </form>
                                        @elseif($p->status == 'proses_kembali')
                                            <form action="{{ route('peminjaman.review_kembali', $p->id) }}" method="POST" class="d-flex gap-2 justify-content-center">
                                                @csrf
                                                <button name="action" value="setuju" class="btn-verify btn-approve">
                                                    <i class="fas fa-check-circle me-1"></i> TERIMA BUKU
                                                </button>
                                                @if($p->status_denda == 'pending_admin')
                                                    <button name="action" value="tolak_denda" class="btn-verify btn-reject" title="Bukti Salah">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                            </form>
                                        @elseif($p->status == 'dikembalikan')
                                            <div class="text-success">
                                                <i class="fas fa-check-circle fa-lg shadow-sm p-2 rounded-circle bg-white"></i>
                                            </div>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <img src="https://illustrations.popsy.co/gray/folder-is-empty.svg" style="width: 150px;" class="mb-3">
                                        <p class="text-muted fw-bold">Belum ada transaksi masuk.</p>
                                    </td>
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