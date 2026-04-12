<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Pustaka - Perpustakaan Digital Masa Kini</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #fff; overflow-x: hidden; color: #1f2937; }
        .container { width: 90%; max-width: 1280px; margin: 0 auto; }

        /* --- Navbar Style --- */
        nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid #f3f4f6;
        }
        .nav-wrapper { display: flex; justify-content: space-between; align-items: center; padding: 18px 0; }
        .logo { font-size: 26px; font-weight: 800; color: #1e40af; text-decoration: none; display: flex; align-items: center; gap: 10px; }
        .logo i { color: #f97316; }
        .nav-links { display: flex; gap: 35px; list-style: none; align-items: center; }
        .nav-links a { text-decoration: none; color: #4b5563; font-weight: 500; font-size: 15px; transition: 0.3s; }
        .nav-links a:hover { color: #1e40af; }
        .btn-login { background: #2563eb; color: white !important; padding: 10px 24px; border-radius: 8px; font-weight: 600; transition: 0.3s; }
        .btn-login:hover { background: #1e40af; }

        @media (max-width: 768px) { .nav-links { display: none; } }
    </style>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
    @yield('style')
</head>
<body>

    @include('Layout.frontend.header')

    <main>
        @yield('content')
    </main>

    @yield('script')
</body>
</html>