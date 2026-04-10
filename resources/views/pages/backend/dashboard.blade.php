@extends('layout.backend.app')

@section('conten')
  <div class="content">
    <div class="page-title">Dashboard</div>

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

    <div class="table-card">
      <div class="table-header" style="padding: 20px; font-weight: bold; border-bottom: 1px solid #eee;">
        Peminjaman Terakhir
      </div>
      <table>
        <thead>
          <tr>
            <th>Anggota</th>
            <th>Nama Anggota</th>
            <th>Judul Buku</th>
            <th>Tgl Kembali</th>
            <th>Tgl Peminjaman</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          {{-- Contoh Statis (Ganti @foreach jika tabel peminjaman sudah ada) --}}
          <tr>
            <td>
              <div class="avatar-placeholder" style="background:#f5a623;">H</div>
            </td>
            <td>Herman Beck</td>
            <td>Anak Kampung</td>
            <td>Maret 18, 2025</td>
            <td>Maret 15, 2025</td>
            <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
          </tr>
          {{-- ... baris lainnya ... --}}
        </tbody>
      </table>

      <div class="pagination-row">
        <span class="showing-text">Menampilkan data terbaru</span>
        <div class="pagination">
          <button class="page-btn">Previous</button>
          <button class="page-btn active">1</button>
          <button class="page-btn">Next</button>
        </div>
      </div>
    </div>

  </div>
@endsection