<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <style>
        body{font-family:system-ui,Arial; background:#f6f7fb; margin:0;}
        .wrap{min-height:100vh; display:flex; align-items:center; justify-content:center;}
        .card{width:360px; background:#fff; padding:24px; border-radius:12px; box-shadow:0 8px 30px rgba(0,0,0,.08);}
        .title{font-size:20px; font-weight:700; margin:0 0 16px;}
        label{display:block; font-size:14px; margin:12px 0 6px;}
        input{width:100%; padding:10px 12px; border:1px solid #d6d9e0; border-radius:10px; outline:none;}
        button{width:100%; margin-top:16px; padding:10px 12px; border:0; border-radius:10px; cursor:pointer; background:#f59e0b; color:white; font-weight:600;}
        .err{color:#b42318; font-size:13px; margin-top:6px;}
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1 class="title">Login</h1>

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" required>
            @error('email') <div class="err">{{ $message }}</div> @enderror

            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password" required>
            @error('password') <div class="err">{{ $message }}</div> @enderror

            <button type="submit">Masuk</button>
        </form>
    </div>
</div>
</body>
</html>
