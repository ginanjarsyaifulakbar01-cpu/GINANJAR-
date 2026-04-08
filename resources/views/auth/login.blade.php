<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KakAdmin Perpus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        .login-header { text-align: center; margin-bottom: 30px; }
        .login-header h1 { color: #444; font-size: 24px; margin-bottom: 10px; }
        .login-header span { color: #888; font-size: 14px; }
        
        .form-group { margin-bottom: 20px; position: relative; }
        .form-group i { position: absolute; left: 15px; top: 38px; color: #764ba2; }
        .form-group label { display: block; margin-bottom: 8px; color: #555; font-weight: 600; font-size: 14px; }
        .form-group input {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            transition: 0.3s;
        }
        .form-group input:focus { border-color: #764ba2; box-shadow: 0 0 5px rgba(118, 75, 162, 0.2); }
        
        .login-btn {
            width: 100%;
            padding: 12px;
            background: #764ba2;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        .login-btn:hover { background: #5a3a7d; transform: translateY(-2px); }
        
        .alert {
            background: #fee2e2;
            color: #dc2626;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border: 1px solid #fecaca;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <h1>Welcome Back!</h1>
        <span>Silakan login untuk masuk ke KakAdmin</span>
    </div>

    {{-- Alert Error --}}
    @if(session()->has('loginError'))
        <div class="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('loginError') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="alert">
            <i class="fas fa-shield-alt"></i> {{ session('error') }}
        </div>
    @endif

    <form action="/login" method="POST">
        @csrf
        <div class="form-group">
            <label>Email Address</label>
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="admin@gmail.com" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="form-group">
            <label>Password</label>
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>

        <button type="submit" class="login-btn">Login Sekarang</button>
    </form>
</div>

</body>
</html>