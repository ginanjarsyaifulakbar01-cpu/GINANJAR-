<nav>
    <div class="container">
        <div class="nav-wrapper">
            <a href="{{ route('landing') }}" class="logo">
                <i class="fas fa-book-reader"></i>
                Perpus<span>Ginx</span>
            </a>

            <ul class="nav-links">
    @auth
        <li><a href="{{ route('home') }}">Beranda</a></li>
        <li><a href="{{ route('katalog') }}">Katalog</a></li>
        {{-- Tambah Link Riwayat di Navigasi Utama --}}
        <li><a href="{{ route('riwayat.pinjam') }}">Riwayat Pinjam</a></li>
        
        {{-- Shortcut ke Admin Panel kalau dia Admin/Petugas --}}
        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'petugas')
            <li><a href="{{ route('admin.dashboard') }}" class="admin-link">Panel Admin</a></li>
        @endif
    @endauth

    @auth
        <li class="profile-dropdown">
            <a href="#" class="user-info-link"> {{-- Ubah route ke # biar gak keganti pas diklik --}}
                <div class="user-info">
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=2563eb&color=fff' }}" 
                         alt="User">
                </div>
            </a>
            
            <div class="dropdown-content">
                <a href="{{ route('profile') }}" class="dropdown-item">
                    <i class="fas fa-user-circle"></i> Profil Saya
                </a>
                {{-- Tambah Link Riwayat di dalam Dropdown juga biar mantap --}}
                <a href="{{ route('riwayat.pinjam') }}" class="dropdown-item">
                    <i class="fas fa-history"></i> Riwayat Pinjam
                </a>

                <hr style="margin: 0; border: 0.5px solid #f1f5f9;">
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </button>
                </form>
            </div>
        </li>
    @else
        <li><a href="{{ route('login') }}" class="btn-login">Masuk</a></li>
    @endauth
</ul>
        </div>
    </div>
</nav>

<style>
    /* Reset & Slim Layout */
    .nav-wrapper {
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 20px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    /* Profile Style Slim */
    .user-info-link {
        text-decoration: none;
        display: block;
    }

    .profile-dropdown {
        position: relative;
        display: inline-block;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 5px 10px;
        border-radius: 50px;
        transition: 0.3s;
    }

    .user-info:hover {
        background: #f1f5f9;
    }

    .user-name {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
    }

    .user-info img {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #2563eb;
    }

    /* Dropdown Content */
    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        top: 100%;
        background-color: white;
        min-width: 150px;
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        border-radius: 12px;
        overflow: hidden;
        z-index: 1000;
        border: 1px solid #e2e8f0;
    }

    .profile-dropdown:hover .dropdown-content {
        display: block;
    }

    /* Item di dalam dropdown */
    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 16px;
        text-decoration: none;
        color: #1e293b;
        font-size: 14px;
        font-weight: 600;
        transition: 0.2s;
    }

    .dropdown-item:hover {
        background: #f8fafc;
        color: #2563eb;
    }

    .logout-btn {
        width: 100%;
        padding: 12px 16px;
        border: none;
        background: white;
        color: #dc2626;
        font-weight: 700;
        text-align: left;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
    }

    .logout-btn:hover {
        background: #fef2f2;
    }

    .admin-link {
        color: #2563eb !important;
        font-weight: 800;
        text-decoration: none;
    }

    .logo {
        font-size: 20px;
        font-weight: 800;
        text-decoration: none;
        color: #1e293b;
    }
    .logo span { color: #2563eb; }
</style>