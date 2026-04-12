@extends('layout.backend.app')

@section('content')
  <div class="content">
    <div class="stat-cards">
      {{-- Card Anggota --}}
      <div class="stat-card orange">
        <div class="stat-info">
          <div class="stat-label">Total Anggota</div>
          <div class="stat-number">{{ $totalUser }}</div>
        </div>
        <div class="stat-icon-wrap"><i class="fas fa-users"></i></div>
      </div>

      {{-- Card Buku --}}
      <div class="stat-card blue">
        <div class="stat-info">
          <div class="stat-label">Total Buku</div>
          <div class="stat-number">{{ $totalBuku }}</div>
        </div>
        <div class="stat-icon-wrap"><i class="fas fa-book"></i></div>
      </div>

      {{-- Card Pinjaman --}}
      <div class="stat-card teal">
        <div class="stat-info">
          <div class="stat-label">Total Pinjaman</div>
          <div class="stat-number">{{ $totalPinjaman }}</div>
        </div>
        <div class="stat-icon-wrap"><i class="fas fa-exchange-alt"></i></div>
      </div>
    </div>

    {{-- Tabel Buku Terpopuler --}}
    <div class="table-card">
      <div class="table-header" style="padding: 20px; font-weight: bold; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
        <span>Buku Paling Populer</span>
        <span class="badge bg-soft-primary text-primary" style="font-size: 0.7rem; background: #eef2ff; padding: 5px 10px; border-radius: 5px;">Top 5 Koleksi</span>
      </div>
      <table>
        <thead>
          <tr>
            <th>Cover</th>
            <th>Judul Buku</th>
            <th>Penulis</th>
            <th class="text-center">Total Dipinjam</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($bukuPopuler as $buku)
          <tr>
            <td>
                @if($buku->cover)
                    {{-- Path disesuaikan: langsung asset($buku->cover) tanpa 'storage/' --}}
                    <img src="{{ asset($buku->cover) }}" style="width: 45px; height: 60px; object-fit: cover; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                @else
                    <div class="avatar-placeholder" style="background:#7c3aff; width: 45px; height: 60px; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: white; font-size: 9px; font-weight: bold;">
                        NO IMG
                    </div>
                @endif
            </td>
            <td>
                <div style="font-weight: 700; color: #1e1b3a;">{{ $buku->judul }}</div>
                <small class="text-muted">{{ $buku->category->nama ?? 'Tanpa Kategori' }}</small>
            </td>
            <td>{{ $buku->penulis }}</td>
            <td class="text-center">
                <span style="font-weight: 800; color: #6366f1; font-size: 1.1rem;">{{ $buku->peminjaman_count }}</span>
                <small class="text-muted" style="font-size: 0.7rem; display: block;">Kali Dipinjam</small>
            </td>
            <td class="text-center">
                {{-- Ganti button jadi tag 'a' agar bisa diklik ke detail --}}
                <a href="{{ route('buku.show', $buku->id) }}" class="action-btn" title="Lihat Detail" style="text-decoration: none; display: inline-block;">
                    <i class="fas fa-external-link-alt"></i>
                </a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center py-5 text-muted">Belum ada data peminjaman tercatat.</td>
          </tr>
          @endforelse
        </tbody>
      </table>

      <div class="pagination-row">
        <span class="showing-text">Menampilkan buku dengan minat tertinggi</span>
        <div class="pagination">
          <a href="{{ route('buku.index') }}" class="page-btn" style="text-decoration: none; color: inherit;">Lihat Semua Buku</a>
        </div>
      </div>
    </div>
  </div>
@endsection