<aside class="sidebar">
  <div class="sidebar-logo">
    <div class="logo-icon">G</div>
    <span class="logo-text">GinxAdmin</span>
  </div>
  
  <nav class="sidebar-nav">
    
    {{-- Dashboard Admin --}}
    <a class="nav-item {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
      <i class="fas fa-home"></i> Dashboard
    </a>

    {{-- Halaman Buku --}}
    <a class="nav-item {{ Route::is('buku.*') || Request::is('admin/buku*') ? 'active' : '' }}" href="{{ route('buku.index') }}">
      <i class="fas fa-book"></i> Halaman Buku
    </a>

    {{-- Peminjaman --}}
    <a class="nav-item {{ Route::is('peminjaman.*') || Request::is('admin/peminjaman*') ? 'active' : '' }}" href="{{ route('peminjaman.index') }}">
      <i class="fas fa-exchange-alt"></i> Peminjaman
    </a>

    {{-- Kategori --}}
    <a class="nav-item {{ Route::is('categories.*') || Request::is('admin/categories*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
      <i class="fas fa-tags"></i> Kategori
    </a>

    {{-- Menu User Khusus Admin --}}
    @auth
      @if(Auth::user()->role === 'admin')
      <a class="nav-item {{ Route::is('user.*') || Request::is('admin/user*') ? 'active' : '' }}" href="{{ route('user.index') }}">
        <i class="fas fa-users"></i> User
      </a>
      @endif
    @endauth

    <hr style="border: 0.5px solid #eeeeee22; margin: 10px 15px;">
    

  </nav>
</aside>

<style>
  /* Styling agar menu yang aktif terlihat menonjol */
  .nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    text-decoration: none;
    color: #cbd5e1;
    font-weight: 500;
    transition: 0.3s;
  }

  .nav-item:hover {
    background-color: rgba(255, 255, 255, 0.05);
    color: #fff;
  }

  .nav-item.active {
    background-color: #7c3aff !important; 
    color: #ffffff !important;
    border-left: 4px solid #b06aff;
  }
  
  .nav-item.active i {
    color: #ffffff !important;
  }

  .sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 10px 0;
  }

  .sidebar-logo {
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .logo-icon {
    background: #7c3aff;
    color: white;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-weight: bold;
  }

  .logo-text {
    font-weight: 800;
    font-size: 1.2rem;
    color: white;
  }
</style>