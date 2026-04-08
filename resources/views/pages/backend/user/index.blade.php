@extends('layout.backend.app')

@section('conten')
    <div class="content">
        <div class="page-title">Halaman Data User</div>

        <div class="table-card">
            <!-- Header -->
            <div class="table-header">
                <div class="table-header-left">Data User</div>

                <div class="table-header-right">
                    <div class="search-wrap">
                        <span class="search-label">SEARCH:</span>
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" placeholder="Cari user...">
                        </div>
                    </div>

                    <a href="{{ route('user.create') }}" class="btn-add">
                        <i class="fas fa-plus"></i>
                        <span>User</span>
                    </a>
                </div>
            </div>

            <!-- Table -->
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                @if($user->img)
                                    <img src="{{ asset('storage/' . $user->img) }}" width="40" style="border-radius:50%;">
                                @else
                                    <div class="book-cover-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                            </td>

                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>

                            <td>
                                @if($user->role == 'admin')
                                    <span class="badge badge-dipinjam">Admin</span>
                                @elseif($user->role == 'petugas')
                                    <span class="badge badge-dikembalikan">Petugas</span>
                                @else
                                    <span class="badge">Anggota</span>
                                @endif
                            </td>

                            <td>
                                <!-- Action -->
                                <a href="{{ route('user.edit', $user->id) }}" class="action-btn">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Yakin hapus?')" class="action-btn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center;">Data kosong</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination-row">
                <span class="showing-text">
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} entries
                </span>

                <div class="pagination">
                    {{ $users->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection