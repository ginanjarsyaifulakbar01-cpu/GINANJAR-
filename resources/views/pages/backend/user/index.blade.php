@extends('layout.backend.app')

@section('content')
<main class="content">

    <section class="table-card shadow-sm border-0 bg-white" style="border-radius: 20px; overflow: hidden;">
        <header class="table-header d-flex flex-wrap align-items-center justify-content-between p-4 border-bottom">
            <h2 class="h5 font-weight-bold mb-3 mb-md-0">Daftar User Terdaftar</h2>

            <div class="table-actions d-flex align-items-center flex-wrap" style="gap: 15px;">
                <div class="search-wrap d-flex align-items-center bg-light px-3 py-2" style="border-radius: 12px; min-width: 250px;">
                    <i class="fas fa-search text-muted mr-2"></i>
                    <input type="text" class="bg-transparent border-0 small w-100" placeholder="Cari nama atau email..." style="outline: none;">
                </div>

                <a href="{{ route('user.create') }}" class="btn btn-primary d-flex align-items-center px-4 shadow-sm" style="border-radius: 12px; gap: 8px; background: #6366f1; border: none;">
                    <i class="fas fa-plus-circle"></i>
                    <span class="font-weight-bold">Tambah User</span>
                </a>
            </div>
        </header>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
                <thead class="bg-light text-uppercase small font-weight-bold text-muted">
                    <tr>
                        <th class="pl-4 py-3" style="width: 80px;">No</th>
                        <th class="py-3">Info User</th>
                        <th class="py-3 text-center">Role</th>
                        <th class="py-3 pr-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td class="pl-4 font-weight-bold text-muted align-middle">
                                {{ $users->firstItem() + $index }}
                            </td>
                            <td class="align-middle">
                                <div class="d-flex align-items-center">
                                    <figure class="avatar-wrapper mb-0 mr-3" style="width: 45px; height: 45px; flex-shrink: 0;">
                                        @if($user->img)
                                            <img src="{{ asset('storage/' . $user->img) }}" 
                                                 alt="{{ $user->name }}" 
                                                 class="rounded-circle shadow-sm" 
                                                 style="width: 100%; height: 100%; object-fit: cover; border: 2px solid #fff;">
                                        @else
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-primary font-weight-bold shadow-sm" 
                                                 style="width: 100%; height: 100%; background: #eef2ff;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </figure>
                                    
                                    <div class="user-details">
                                        <div class="font-weight-bold text-dark mb-0" style="font-size: 0.95rem;">{{ $user->name }}</div>
                                        <small class="text-muted"><i class="far fa-envelope mr-1" style="font-size: 0.8rem;"></i>{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                @php
                                    $roleClasses = [
                                        'admin' => 'bg-soft-danger text-danger',
                                        'petugas' => 'bg-soft-info text-info',
                                        'anggota' => 'bg-soft-success text-success'
                                    ];
                                    $class = $roleClasses[$user->role] ?? 'bg-light text-muted';
                                @endphp
                                <span class="badge px-3 py-2 text-capitalize shadow-xs" style="border-radius: 8px; font-size: 11px; {{ $class }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="pr-4 text-center align-middle">
                                <div class="d-flex justify-content-center" style="gap: 8px;">
                                    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-light text-primary shadow-xs" title="Edit User" style="border-radius: 10px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Yakin hapus user ini?')" class="btn btn-sm btn-light text-danger shadow-xs" title="Hapus User" style="border-radius: 10px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border: none;">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3 opacity-25">
                                <p class="text-muted font-italic mb-0">Belum ada data user yang terdaftar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <footer class="p-4 bg-light border-top d-flex flex-wrap justify-content-between align-items-center">
            <p class="small text-muted mb-0">
                Menampilkan <strong>{{ $users->firstItem() }}</strong> sampai <strong>{{ $users->lastItem() }}</strong> dari <strong>{{ $users->total() }}</strong> user.
            </p>
            <nav aria-label="Navigasi Halaman" class="mt-2 mt-md-0">
                {{ $users->links() }}
            </nav>
        </footer>
    </section>
</main>

<style>
    /* Custom Color & UI Utilities */
    .bg-soft-danger { background-color: #fee2e2; color: #b91c1c; }
    .bg-soft-info { background-color: #e0f2fe; color: #0369a1; }
    .bg-soft-success { background-color: #dcfce7; color: #15803d; }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .table-hover tbody tr:hover { background-color: #fbfcfe; transition: 0.2s ease-in-out; }
    
    /* Ensure image fits perfect in circle */
    .avatar-wrapper img {
        display: block;
    }
    
    /* Breadcrumb tweak */
    .breadcrumb-item + .breadcrumb-item::before {
        content: "/";
        color: #cbd5e1;
    }
</style>
@endsection