@extends('layout.backend.app')

@section('content')
  <div class="content">
    {{-- Dashboard Stats --}}
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
    <div class="table-card" style="background: #fff; border-radius: 14px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); overflow: hidden;">
      <div class="table-header"
        style="padding: 20px; font-weight: bold; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center;">
        <span style="font-size: 1.1rem; color: #1e1b3a;">Buku Paling Populer</span>
        <span class="badge"
          style="font-size: 0.7rem; background: #eef2ff; color: #6366f1; padding: 5px 10px; border-radius: 5px; font-weight: 700;">Top 5 Koleksi</span>
      </div>
      
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: #fcfcfd; border-bottom: 1px solid #eee;">
            <th style="padding: 15px; text-align: left; font-size: 12px; color: #64748b; text-transform: uppercase;">Cover</th>
            <th style="padding: 15px; text-align: left; font-size: 12px; color: #64748b; text-transform: uppercase;">Judul Buku</th>
            <th style="padding: 15px; text-align: left; font-size: 12px; color: #64748b; text-transform: uppercase;">Penulis</th>
            <th style="padding: 15px; text-align: center; font-size: 12px; color: #64748b; text-transform: uppercase;">Total Dipinjam</th>
            <th style="padding: 15px; text-align: center; font-size: 12px; color: #64748b; text-transform: uppercase;">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($bukuPopuler as $buku)
            <tr style="border-bottom: 1px solid #f8fafc;">
              <td style="padding: 15px;">
                @if($buku->cover)
                  <img src="{{ asset($buku->cover) }}"
                    style="width: 45px; height: 60px; object-fit: cover; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                @else
                  <div class="avatar-placeholder"
                    style="background:#7c3aff; width: 45px; height: 60px; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: white; font-size: 9px; font-weight: bold;">
                    NO IMG
                  </div>
                @endif
              </td>
              <td style="padding: 15px;">
                <div style="font-weight: 700; color: #1e1b3a;">{{ $buku->judul }}</div>
                <small class="text-muted" style="color: #94a3b8;">{{ $buku->category->nama ?? 'Tanpa Kategori' }}</small>
              </td>
              <td style="padding: 15px; color: #475569;">{{ $buku->penulis }}</td>
              <td style="padding: 15px; text-align: center;">
                <span style="font-weight: 800; color: #6366f1; font-size: 1.1rem;">{{ $buku->peminjaman_count }}</span>
                <small class="text-muted" style="font-size: 0.7rem; display: block; color: #94a3b8;">Kali Dipinjam</small>
              </td>
              <td style="padding: 15px; text-align: center;">
                {{-- PERUBAHAN DI SINI: Ikon diganti jadi MATA (fa-eye) --}}
                <a href="{{ route('buku.show', $buku->id) }}" class="action-btn" title="Lihat Detail"
                  style="text-decoration: none; display: inline-block; color: #7c3aff; transition: 0.3s; font-size: 1.1rem;">
                  <i class="fas fa-eye"></i>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" style="padding: 50px; text-align: center; color: #94a3b8;">Belum ada data peminjaman tercatat.</td>
            </tr>
          @endforelse
        </tbody>
      </table>

      <div class="pagination-row" style="padding: 20px; display: flex; justify-content: space-between; align-items: center; background: #fcfcfd;">
        <span class="showing-text" style="font-size: 12px; color: #64748b;">Menampilkan buku dengan minat tertinggi</span>
        <div class="pagination">
          <a href="{{ route('buku.index') }}" class="page-btn" 
             style="text-decoration: none; color: #7c3aff; font-size: 12px; font-weight: 700; border: 1px solid #7c3aff; padding: 6px 15px; border-radius: 6px; transition: 0.3s;">
             Lihat Semua Buku
          </a>
        </div>
      </div>
    </div>
  </div>
@endsection