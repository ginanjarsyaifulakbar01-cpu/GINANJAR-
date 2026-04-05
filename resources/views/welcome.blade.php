@extends('layout.app')
@section('conten')
 <!-- CONTENT -->
  <div class="content">
    <div class="page-title">Dashboard petugas</div>

    <!-- STAT CARDS -->
    <div class="stat-cards">
      <div class="stat-card orange">
        <div class="stat-info">
          <div class="stat-label">Total Anggota</div>
          <div class="stat-number">100</div>
        </div>
        <div class="stat-icon-wrap"><i class="fas fa-users"></i></div>
      </div>
      <div class="stat-card blue">
        <div class="stat-info">
          <div class="stat-label">Total Buku</div>
          <div class="stat-number">100</div>
        </div>
        <div class="stat-icon-wrap"><i class="fas fa-book"></i></div>
      </div>
      <div class="stat-card teal">
        <div class="stat-info">
          <div class="stat-label">Total Pinjaman</div>
          <div class="stat-number">100</div>
        </div>
        <div class="stat-icon-wrap"><i class="fas fa-exchange-alt"></i></div>
      </div>
    </div>

    <!-- TABLE -->
    <div class="table-card">
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
          <tr>
            <td>
              <div class="avatar-placeholder" style="background:#e84393;">M</div>
            </td>
            <td>Mevsey Adam</td>
            <td>Sang Pejuang</td>
            <td>April 10, 2025</td>
            <td>April 16, 2025</td>
            <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
          </tr>
          <tr>
            <td>
              <div class="avatar-placeholder" style="background:#26c6a6;">J</div>
            </td>
            <td>John Richards</td>
            <td>Pekelang</td>
            <td>Mei 11, 2025</td>
            <td>Mei 15, 2025</td>
            <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
          </tr>
          <tr>
            <td>
              <div class="avatar-placeholder" style="background:#4a90e2;">P</div>
            </td>
            <td>Peter Meggik</td>
            <td>Kancil Yang Pintar</td>
            <td>Juni 5, 2025</td>
            <td>Juni 12, 2025</td>
            <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
          </tr>
          <tr>
            <td>
              <div class="avatar-placeholder" style="background:#b06aff;">E</div>
            </td>
            <td>Edward</td>
            <td>Anak Kancil</td>
            <td>Juli 5, 2025</td>
            <td>Juli 6, 2025</td>
            <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
          </tr>
          <tr>
            <td>
              <div class="avatar-placeholder" style="background:#e8792a;">J</div>
            </td>
            <td>John Doe</td>
            <td>—</td>
            <td>Agustus 1, 2025</td>
            <td>Agustus 5, 2025</td>
            <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
          </tr>
          <tr>
            <td>
              <div class="avatar-placeholder" style="background:#f5364f;">H</div>
            </td>
            <td>Henry Taro</td>
            <td>—</td>
            <td>—</td>
            <td>—</td>
            <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
          </tr>
        </tbody>
      </table>

      <!-- PAGINATION -->
      <div class="pagination-row">
        <span class="showing-text">Showing top 7 entries</span>
        <div class="pagination">
          <button class="page-btn">Previous</button>
          <button class="page-btn active">1</button>
          <button class="page-btn">2</button>
          <button class="page-btn">3</button>
          <button class="page-btn">Next</button>
        </div>
      </div>
    </div>

  </div>
@endsection