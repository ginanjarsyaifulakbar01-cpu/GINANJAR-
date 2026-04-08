@extends('layout.backend.app')

@section('conten')
  <div class="content">
    <div class="page-title">Halaman Data Buku</div>

    <div class="table-card">

      <!-- HEADER -->
      <div class="table-header">
        <div class="table-header-left">Data Buku</div>

        <div class="table-header-right">

          <!-- SEARCH FORM -->
          <form method="GET" action="{{ route('buku.index') }}" class="search-wrap">
            <span class="search-label">SEARCH:</span>
            <div class="search-box">
              <i class="fas fa-search"></i>
              <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari buku...">
            </div>
          </form>

          <a href="{{ route('buku.create') }}" class="btn-add">
            <i class="fas fa-plus"></i>
            <span>Buku</span>
          </a>

        </div>
      </div>

      <!-- TABLE -->
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Cover</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Tahun</th>
            <th>Stok</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
          @forelse($bukus as $index => $buku)
            <tr>
              <td>{{ $bukus->firstItem() + $index }}</td>

              <!-- COVER -->
              <td>
                @if($buku->cover)
                  <img src="{{ asset('storage/' . $buku->cover) }}" width="40" style="border-radius:6px;">
                @else
                  <div class="book-cover-placeholder">
                    <i class="fas fa-book"></i>
                  </div>
                @endif
              </td>

              <td>{{ $buku->judul }}</td>
              <td>{{ $buku->penulis }}</td>
              <td>{{ $buku->tahun_terbit }}</td>
              <td>{{ $buku->stok }}</td>

              <!-- STATUS -->
              <td>
                @if($buku->stok > 0)
                  <span class="badge badge-dikembalikan">Tersedia</span>
                @else
                  <span class="badge badge-dipinjam">Habis</span>
                @endif
              </td>

              <!-- ACTION -->
              <td>
                <a href="{{ route('buku.edit', $buku->id) }}" class="action-btn">
                  <i class="fas fa-edit"></i>
                </a>

                <form action="{{ route('buku.destroy', $buku->id) }}" method="POST" style="display:inline;">
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
              <td colspan="8" style="text-align:center;">Data buku kosong</td>
            </tr>
          @endforelse
        </tbody>
      </table>

      <!-- PAGINATION -->
      <div class="pagination-row">
        <span class="showing-text">
          Showing {{ $bukus->firstItem() ?? 0 }} to {{ $bukus->lastItem() ?? 0 }} entries
        </span>

        <div class="pagination">
          {{ $bukus->links() }}
        </div>
      </div>

    </div>
  </div>
@endsection