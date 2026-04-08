<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KakAdmin - Dashboard Petugas</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    a.btn-add,
    a.btn-add:hover {
      text-decoration: none !important;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: #f5f0f7;
      display: flex;
      min-height: 100vh;
      font-size: 13px;
      color: #333;
    }

    /* SIDEBAR */
    .sidebar {
      width: 190px;
      background: #1e1b3a;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      padding: 0;
      flex-shrink: 0;
    }

    .sidebar-logo {
      padding: 18px 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .logo-icon {
      width: 32px;
      height: 32px;
      background: linear-gradient(135deg, #b06aff, #7c3aff);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      color: #fff;
      font-size: 14px;
    }

    .logo-text {
      color: #fff;
      font-weight: 700;
      font-size: 15px;
      letter-spacing: 0.5px;
    }

    .sidebar-nav {
      padding: 14px 0;
      flex: 1;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 22px;
      color: rgba(255, 255, 255, 0.55);
      cursor: pointer;
      transition: all 0.2s;
      font-size: 13px;
      text-decoration: none;
    }

    .nav-item:hover {
      color: #fff;
      background: rgba(255, 255, 255, 0.07);
    }

    .nav-item.active {
      color: #fff;
      background: rgba(255, 255, 255, 0.1);
      border-left: 3px solid #b06aff;
    }

    .nav-item i {
      width: 16px;
      text-align: center;
      font-size: 14px;
    }

    /* MAIN */
    .main {
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    /* TOPBAR */
    .topbar {
      background: #fff;
      padding: 12px 28px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.07);
    }

    .topbar-icons {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .icon-circle {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 14px;
    }

    .icon-circle.blue {
      background: #4a90e2;
      color: #fff;
    }

    .icon-circle.orange {
      background: #f5a623;
      color: #fff;
    }

    .icon-circle.dark {
      background: #1e1b3a;
      color: #fff;
    }

    .topbar-user {
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
    }

    .user-avatar {
      width: 34px;
      height: 34px;
      background: linear-gradient(135deg, #f5a623, #e8792a);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 15px;
    }

    .user-name {
      font-size: 13px;
      font-weight: 500;
      color: #333;
    }

    .user-arrow {
      color: #aaa;
      font-size: 11px;
    }

    /* CONTENT */
    .content {
      padding: 28px 30px;
      flex: 1;
    }

    .page-title {
      font-size: 18px;
      font-weight: 600;
      color: #1e1b3a;
      margin-bottom: 22px;
    }

    /* STAT CARDS */
    .stat-cards {
      display: flex;
      gap: 18px;
      margin-bottom: 28px;
    }

    .stat-card {
      flex: 1;
      border-radius: 14px;
      padding: 20px 22px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: #fff;
      position: relative;
      overflow: hidden;
    }

    .stat-card.orange {
      background: linear-gradient(135deg, #f5a623, #e8792a);
    }

    .stat-card.blue {
      background: linear-gradient(135deg, #4a90e2, #3a6bc7);
    }

    .stat-card.teal {
      background: linear-gradient(135deg, #26c6a6, #1da98c);
    }

    .stat-card::before {
      content: '';
      position: absolute;
      top: -20px;
      right: -20px;
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.15);
    }

    .stat-info .stat-label {
      font-size: 12px;
      opacity: 0.9;
      margin-bottom: 4px;
    }

    .stat-info .stat-number {
      font-size: 28px;
      font-weight: 700;
      line-height: 1;
    }

    .stat-icon-wrap {
      width: 44px;
      height: 44px;
      background: rgba(255, 255, 255, 0.25);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
    }

    /* TABLE CARD */
    .table-card {
      background: #fff;
      border-radius: 14px;
      padding: 22px 24px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead tr th {
      text-align: left;
      font-size: 12px;
      font-weight: 600;
      color: #888;
      padding: 0 12px 12px 12px;
      border-bottom: 1px solid #f0f0f0;
    }

    tbody tr td {
      padding: 11px 12px;
      font-size: 12.5px;
      color: #444;
      border-bottom: 1px solid #f8f8f8;
      vertical-align: middle;
    }

    tbody tr:last-child td {
      border-bottom: none;
    }

    tbody tr:hover {
      background: #faf8ff;
    }

    .member-info {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      object-fit: cover;
      flex-shrink: 0;
    }

    .avatar-placeholder {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 13px;
      font-weight: 600;
      color: #fff;
      flex-shrink: 0;
    }

    .action-btn {
      background: none;
      border: none;
      cursor: pointer;
      color: #888;
      font-size: 16px;
      padding: 4px 8px;
      border-radius: 6px;
      transition: background 0.15s;
    }

    .action-btn:hover {
      background: #f0eeff;
      color: #7c3aff;
    }

    /* PAGINATION */
    .pagination-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 18px;
    }

    .showing-text {
      font-size: 12px;
      color: #aaa;
    }

    .pagination {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .page-btn {
      padding: 6px 13px;
      border: 1px solid #e5e5e5;
      background: #fff;
      border-radius: 7px;
      font-size: 12px;
      cursor: pointer;
      color: #555;
      transition: all 0.15s;
      font-family: 'Poppins', sans-serif;
    }

    .page-btn:hover {
      background: #f0eeff;
      border-color: #b06aff;
      color: #7c3aff;
    }

    .page-btn.active {
      background: #7c3aff;
      border-color: #7c3aff;
      color: #fff;
      font-weight: 600;
    }
  </style>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: #f5f0f7;
      display: flex;
      min-height: 100vh;
      font-size: 13px;
      color: #333;
    }

    /* SIDEBAR */
    .sidebar {
      width: 190px;
      background: #1e1b3a;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      flex-shrink: 0;
    }

    .sidebar-logo {
      padding: 18px 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .logo-icon {
      width: 32px;
      height: 32px;
      background: linear-gradient(135deg, #b06aff, #7c3aff);
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      color: #fff;
      font-size: 14px;
    }

    .logo-text {
      color: #fff;
      font-weight: 700;
      font-size: 15px;
    }

    .sidebar-nav {
      padding: 14px 0;
      flex: 1;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 22px;
      color: rgba(255, 255, 255, 0.55);
      cursor: pointer;
      transition: all 0.2s;
      font-size: 13px;
      text-decoration: none;
    }

    .nav-item:hover {
      color: #fff;
      background: rgba(255, 255, 255, 0.07);
    }

    .nav-item.active {
      color: #fff;
      background: rgba(255, 255, 255, 0.1);
      border-left: 3px solid #b06aff;
    }

    .nav-item i {
      width: 16px;
      text-align: center;
      font-size: 14px;
    }

    /* MAIN */
    .main {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    /* TOPBAR */
    .topbar {
      background: #fff;
      padding: 12px 28px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.07);
    }

    .topbar-icons {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .icon-circle {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 14px;
    }

    .icon-circle.blue {
      background: #4a90e2;
      color: #fff;
    }

    .icon-circle.orange {
      background: #f5a623;
      color: #fff;
    }

    .icon-circle.dark {
      background: #1e1b3a;
      color: #fff;
    }

    .topbar-user {
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
    }

    .user-avatar {
      width: 34px;
      height: 34px;
      background: linear-gradient(135deg, #f5a623, #e8792a);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: 15px;
    }

    .user-name {
      font-size: 13px;
      font-weight: 500;
      color: #333;
    }

    .user-arrow {
      color: #aaa;
      font-size: 11px;
    }

    /* CONTENT */
    .content {
      padding: 28px 30px;
      flex: 1;
    }

    .page-title {
      font-size: 18px;
      font-weight: 600;
      color: #1e1b3a;
      margin-bottom: 22px;
    }

    /* TABLE CARD */
    .table-card {
      background: #fff;
      border-radius: 14px;
      padding: 22px 24px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
    }

    .table-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .table-header-left {
      font-size: 14px;
      font-weight: 600;
      color: #1e1b3a;
    }

    .table-header-right {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .search-wrap {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .search-label {
      font-size: 12px;
      font-weight: 500;
      color: #555;
    }

    .search-box {
      display: flex;
      align-items: center;
      border: 1.5px solid #ddd;
      border-radius: 8px;
      padding: 5px 10px;
      gap: 7px;
      background: #fff;
    }

    .search-box i {
      color: #aaa;
      font-size: 13px;
    }

    .search-box input {
      border: none;
      outline: none;
      font-family: 'Poppins', sans-serif;
      font-size: 12.5px;
      color: #333;
      width: 160px;
    }

    .btn-add {
      background: #4a90e2;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 8px 18px;
      font-family: 'Poppins', sans-serif;
      font-size: 12.5px;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 7px;
      transition: background 0.2s;
    }

    .btn-add:hover {
      background: #3a6bc7;
    }

    /* TABLE */
    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead tr th {
      text-align: left;
      font-size: 12px;
      font-weight: 600;
      color: #888;
      padding: 0 12px 12px 12px;
      border-bottom: 1px solid #f0f0f0;
    }

    tbody tr td {
      padding: 11px 12px;
      font-size: 12.5px;
      color: #444;
      border-bottom: 1px solid #f8f8f8;
      vertical-align: middle;
    }

    tbody tr:last-child td {
      border-bottom: none;
    }

    tbody tr:hover {
      background: #faf8ff;
    }

    .book-cover {
      width: 38px;
      height: 48px;
      border-radius: 6px;
      object-fit: cover;
      background: #eee;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .book-cover-placeholder {
      width: 38px;
      height: 48px;
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      color: #fff;
      flex-shrink: 0;
    }

    /* Status badges */
    .badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
      text-align: center;
      min-width: 72px;
    }

    .badge-dipinjam {
      background: #4a90e2;
      color: #fff;
    }

    .badge-dikembalikan {
      background: #f5364f;
      color: #fff;
    }

    .action-btn {
      background: none;
      border: none;
      cursor: pointer;
      color: #888;
      font-size: 16px;
      padding: 4px 8px;
      border-radius: 6px;
      transition: background 0.15s;
    }

    .action-btn:hover {
      background: #f0eeff;
      color: #7c3aff;
    }

    /* PAGINATION */
    .pagination-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-top: 18px;
    }

    .showing-text {
      font-size: 12px;
      color: #aaa;
    }

    .pagination {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .page-btn {
      padding: 6px 13px;
      border: 1px solid #e5e5e5;
      background: #fff;
      border-radius: 7px;
      font-size: 12px;
      cursor: pointer;
      color: #555;
      transition: all 0.15s;
      font-family: 'Poppins', sans-serif;
    }

    .page-btn:hover {
      background: #f0eeff;
      border-color: #b06aff;
      color: #7c3aff;
    }

    .page-btn.active {
      background: #7c3aff;
      border-color: #7c3aff;
      color: #fff;
      font-weight: 600;
    }
  </style>

</head>
@stack('styles') {{-- WAJIB ADA INI SUPAYA CSS DARI HALAMAN LAIN BISA MASUK --}}
<body>

  @include('layout.backend.sidebar')
  <!-- MAIN AREA -->
  <div class="main">

    <!-- TOPBAR -->
    <header class="topbar">
  <div class="topbar-icons">
    <div class="icon-circle blue"><i class="fas fa-circle"></i></div>
    <div class="icon-circle orange"><i class="fas fa-circle"></i></div>
    <div class="icon-circle dark"><i class="fas fa-circle"></i></div>
  </div>
  
  <div class="topbar-user">
    {{-- Foto Profil Dinamis --}}
    @if(Auth::user()->img)
      <img src="{{ asset('storage/' . Auth::user()->img) }}" alt="Profile" class="user-avatar" style="object-fit: cover;">
    @else
      <div class="user-avatar">
        {{-- Ambil Inisial Nama Depan --}}
        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
      </div>
    @endif

    {{-- Nama User Dinamis --}}
    <span class="user-name">{{ Auth::user()->name }}</span>
    
    {{-- Tambahkan Dropdown Logout Sekalian Biar Fungsional --}}
    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" style="background: none; border: none; cursor: pointer; color: #aaa; margin-left: 10px;">
            <i class="fas fa-sign-out-alt"></i>
        </button>
    </form>
  </div>
</header>

    @yield('conten')
  </div>

</body>

</html>