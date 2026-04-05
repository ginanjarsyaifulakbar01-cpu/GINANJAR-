
  @extends('layout.app')
@section('conten')
   <!-- CONTENT -->
  <div class="content">
    <div class="page-title">Halaman Data Buku</div>

    <div class="table-card">
      <!-- Table Header -->
      <div class="table-header">
        <div class="table-header-left">History Buku Peminjaman</div>
        <div class="table-header-right">
          <div class="search-wrap">
            <span class="search-label">SERCH:</span>
            <div class="search-box">
              <i class="fas fa-search"></i>
              <input type="text" placeholder="">
            </div>
          </div>
          <a href="/tambahbuku"> <button class="btn-add">
            <i class="fas fa-plus"></i> Buku
          </button></a>
        </div>
      </div>

      <!-- Table -->
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Cover</th>
            <th>Judul</th>
            <th>Status</th>
            <th>Penulis</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td>
              <div class="book-cover-placeholder" style="background: linear-gradient(135deg, #f5a623, #e8792a);">
                <i class="fas fa-book" style="font-size:16px;"></i>
              </div>
            </td>
            <td>Sang Kancil</td>
            <td><span class="badge badge-dipinjam">Dipinjam</span></td>
            <td>Ahmad Maulana</td>
            <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
          </tr>
          <tr>
            <td>2</td>
            <td>
              <div class="book-cover-placeholder" style="background: linear-gradient(135deg, #4a90e2, #3a6bc7);">
                <i class="fas fa-book" style="font-size:16px;"></i>
              </div>
            </td>
            <td>Pemburu</td>
            <td><span class="badge badge-dipinjam">Dipinjam</span></td>
            <td>Malik Nugraha</td>
            <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
          </tr>
          <tr>
            <td>3</td>
            <td>
              <div class="book-cover-placeholder" style="background: linear-gradient(135deg, #26c6a6, #1da98c);">
                <i class="fas fa-book" style="font-size:16px;"></i>
              </div>
            </td>
            <td>Petualang</td>
            <td><span class="badge badge-dikembalikan">Dikembali</span></td>
            <td>Maulana</td>
            <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
          </tr>
          <tr>
            <td>4</td>
            <td>
              <div class="book-cover-placeholder" style="background: linear-gradient(135deg, #b06aff, #7c3aff);">
                <i class="fas fa-book" style="font-size:16px;"></i>
              </div>
            </td>
            <td>Anak Durhaka</td>
            <td><span class="badge badge-dipinjam">Dipinjam</span></td>
            <td>Angel</td>
            <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
          </tr>
          <tr>
            <td>5</td>
            <td>
              <div class="book-cover-placeholder" style="background: linear-gradient(135deg, #f5364f, #c0152a);">
                <i class="fas fa-book" style="font-size:16px;"></i>
              </div>
            </td>
            <td>Maling Kundang</td>
            <td><span class="badge badge-dikembalikan">Dikembali</span></td>
            <td>Madara Uciha</td>
            <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="pagination-row">
        <span class="showing-text">Showing 1 to 5 entries</span>
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