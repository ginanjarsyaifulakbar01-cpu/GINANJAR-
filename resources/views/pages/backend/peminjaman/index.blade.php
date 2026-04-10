@extends('Layout.backend.app')

@section('conten')
<div class="container-fluid px-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
            <i class="fas fa-book-reader text-primary mr-2"></i>Manajemen Peminjaman
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Peminjaman</li>
            </ol>
        </nav>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px overflow: hidden;">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list mr-1"></i> Antrean Request Buku
            </h6>
        </div>
        <div class="card-body">
            {{-- Alert Section --}}
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle" width="100%" cellspacing="0">
                    <thead class="bg-light">
                        <tr class="text-secondary small font-weight-bold text-uppercase">
                            <th class="border-0">Peminjam</th>
                            <th class="border-0">Informasi Buku</th>
                            <th class="border-0">Tgl Request</th>
                            <th class="border-0">Status</th>
                            <th class="border-0 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $p)
                        <tr>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm mr-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                        <i class="fas fa-user-alt small"></i>
                                    </div>
                                    <div>
                                        <span class="font-weight-bold text-dark d-block">{{ $p->user->name ?? 'User Unknown' }}</span>
                                        <small class="text-muted text-lowercase">{{ $p->user->email ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                <span class="text-dark font-weight-500">{{ $p->buku->judul ?? 'Tanpa Judul' }}</span>
                                <br><small class="badge badge-light border">ID: {{ $p->buku_id }}</small>
                            </td>
                            <td class="align-middle">
                                <span class="text-secondary small">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    {{ $p->tgl_request ? \Carbon\Carbon::parse($p->tgl_request)->format('d/m/Y') : '-' }}
                                </span>
                            </td>
                            <td class="align-middle">
                                @php
                                    $statusConfig = [
                                        'pending' => ['bg' => 'warning', 'icon' => 'clock'],
                                        'disetujui' => ['bg' => 'success', 'icon' => 'check-double'],
                                        'ditolak' => ['bg' => 'danger', 'icon' => 'times-circle'],
                                        'dikembalikan' => ['bg' => 'info', 'icon' => 'undo-alt']
                                    ];
                                    $conf = $statusConfig[$p->status] ?? ['bg' => 'secondary', 'icon' => 'info-circle'];
                                @endphp
                                <span class="badge badge-{{ $conf['bg'] }} px-3 py-2 shadow-xs" style="border-radius: 30px; font-weight: 500;">
                                    <i class="fas fa-{{ $conf['icon'] }} mr-1"></i> {{ strtoupper($p->status) }}
                                </span>
                            </td>
                            <td class="align-middle text-center">
                                @if($p->status == 'pending')
                                    <div class="d-flex justify-content-center">
                                        <form action="{{ route('peminjaman.approve', $p->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success shadow-sm mr-2 rounded-pill px-3" onclick="return confirm('Setujui peminjaman ini?')">
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('peminjaman.reject', $p->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Tolak request ini?')">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                @elseif($p->status == 'disetujui')
                                    <form action="{{ route('peminjaman.kembali', $p->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary shadow-sm rounded-pill px-4" onclick="return confirm('Konfirmasi pengembalian?')">
                                            <i class="fas fa-sign-in-alt mr-1"></i> Kembalikan Buku
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted font-italic small">No Action</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-folder-open fa-3x text-light mb-3"></i>
                                    <p class="text-secondary">Belum ada request peminjaman masuk pagi ini, Ngap!</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .font-weight-500 { font-weight: 500; }
    .shadow-xs { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important; }
    .table thead th { border-top: none; }
    .avatar-sm { font-size: 0.8rem; }
</style>
@endsection