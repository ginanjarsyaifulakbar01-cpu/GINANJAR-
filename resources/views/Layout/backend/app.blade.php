<!DOCTYPE html>
<html lang="id">

<head>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8f9fc; }
        .main { padding: 20px; width: 100%; }
    </style>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>KakAdmin - Dashboard Petugas</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    /* CSS ASLI KAMU - TIDAK DIHAPUS */
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

    .main {
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

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

    .icon-circle.blue { background: #4a90e2; color: #fff; }
    .icon-circle.orange { background: #f5a623; color: #fff; }
    .icon-circle.dark { background: #1e1b3a; color: #fff; }

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

    /* STAT CARDS, TABLE, PAGINATION DLL (SEMUA TETAP ADA) */
    .stat-cards { display: flex; gap: 18px; margin-bottom: 28px; }
    .stat-card { flex: 1; border-radius: 14px; padding: 20px 22px; display: flex; align-items: center; justify-content: space-between; color: #fff; position: relative; overflow: hidden; }
    .stat-card.orange { background: linear-gradient(135deg, #f5a623, #e8792a); }
    .stat-card.blue { background: linear-gradient(135deg, #4a90e2, #3a6bc7); }
    .stat-card.teal { background: linear-gradient(135deg, #26c6a6, #1da98c); }
    .stat-card::before { content: ''; position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; border-radius: 50%; background: rgba(255, 255, 255, 0.15); }
    .stat-info .stat-label { font-size: 12px; opacity: 0.9; margin-bottom: 4px; }
    .stat-info .stat-number { font-size: 28px; font-weight: 700; line-height: 1; }
    .stat-icon-wrap { width: 44px; height: 44px; background: rgba(255, 255, 255, 0.25); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }

    .table-card { background: #fff; border-radius: 14px; padding: 22px 24px; box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06); }
    table { width: 100%; border-collapse: collapse; }
    thead tr th { text-align: left; font-size: 12px; font-weight: 600; color: #888; padding: 0 12px 12px 12px; border-bottom: 1px solid #f0f0f0; }
    tbody tr td { padding: 11px 12px; font-size: 12.5px; color: #444; border-bottom: 1px solid #f8f8f8; vertical-align: middle; }
    tbody tr:hover { background: #faf8ff; }

    .pagination-row { display: flex; align-items: center; justify-content: space-between; margin-top: 18px; }
    .page-btn { padding: 6px 13px; border: 1px solid #e5e5e5; background: #fff; border-radius: 7px; font-size: 12px; cursor: pointer; color: #555; font-family: 'Poppins', sans-serif; }
    .page-btn.active { background: #7c3aff; border-color: #7c3aff; color: #fff; font-weight: 600; }
  </style>

  @stack('styles')
</head>

<body>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

  @include('Layout.backend.sidebar')

  <div class="main">
    @include('layout.backend.header')

    {{-- Tempat konten halaman lain muncul --}}
    @yield('content')
  </div>

</body>
</html>