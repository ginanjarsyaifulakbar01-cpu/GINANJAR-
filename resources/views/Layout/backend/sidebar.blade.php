<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon">K</div>
    <span class="logo-text">kakadmin</span>
  </div>
  
  <nav class="sidebar-nav">
    
    {{-- Dashboard --}}
    <a class="nav-item {{ Request::is('/') || Request::is('dashboard') ? 'active' : '' }}" href="/">
      <i class="fas fa-home"></i> Dashboard
    </a>

    {{-- Halaman Buku --}}
    <a class="nav-item {{ Route::is('buku.*') || Request::is('buku*') ? 'active' : '' }}" href="{{ route('buku.index') }}">
      <i class="fas fa-book"></i> Halaman Buku
    </a>

    {{-- Peminjaman --}}
    <a class="nav-item {{ Request::is('peminjaman*') ? 'active' : '' }}" href="#">
      <i class="fas fa-exchange-alt"></i> Peminjaman
    </a>

    {{-- Kategori (Disesuaikan agar pasti aktif) --}}
    <a class="nav-item {{ Route::is('categories.*') || Request::is('*categories*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
      <i class="fas fa-tags"></i> Kategori
    </a>

    {{-- Menu User Hanya Muncul Jika Role-nya Admin --}}
@if(Auth::user()->role === 'admin')
    <a class="nav-item {{ Route::is('user.*') || Request::is('user*') ? 'active' : '' }}" href="{{ route('user.index') }}">
      <i class="fas fa-users"></i> User
    </a>
@endif

  </nav>
</aside>

<style>
  /* Tambahan CSS sedikit biar class .active kelihatan jelas */
  .nav-item.active {
    background-color: #7c3aff !important; /* Warna ungu sesuai tema form kamu */
    color: #ffffff !important;
  }
  
  .nav-item.active i {
    color: #ffffff !important;
  }
</style>