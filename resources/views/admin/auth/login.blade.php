<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login · Aiyra Constructions</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo-icon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}?v=1.0">
</head>
<body class="login-page">
    <div class="login">
        <div class="login__art">
            <div class="login__shapes"><span></span><span></span><span></span></div>
            <img src="{{ asset('assets/images/logo-white.png') }}" alt="Aiyra Constructions" class="login__logo">
            <h2>Manage your website<br>with ease.</h2>
            <p>Upload projects, respond to enquiries and update every page of the Aiyra Constructions website from one place.</p>
            <ul>
                <li><i class="bi bi-buildings"></i> Project uploads with gallery</li>
                <li><i class="bi bi-envelope-paper"></i> Enquiry inbox & CSV export</li>
                <li><i class="bi bi-sliders"></i> Home, About & Footer settings</li>
            </ul>
        </div>
        <div class="login__form-wrap">
            <form method="POST" action="{{ route('admin.login.submit') }}" class="login__form">
                @csrf
                <img src="{{ asset('assets/images/logo.png') }}" alt="" class="login__logo-sm">
                <h1>Welcome back 👋</h1>
                <p class="muted">Sign in to the admin dashboard</p>

                @if(session('success'))<div class="alert alert--success"><i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span></div>@endif
                @error('email')<div class="alert alert--error"><i class="bi bi-exclamation-triangle-fill"></i><span>{{ $message }}</span></div>@enderror

                <label class="form-label">Email address</label>
                <div class="input-icon"><i class="bi bi-envelope"></i><input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus placeholder="admin@aiyra.com"></div>

                <label class="form-label">Password</label>
                <div class="input-icon"><i class="bi bi-lock"></i><input type="password" name="password" class="form-control" required placeholder="••••••••" id="pwd">
                    <button type="button" class="input-icon__toggle" onclick="var p=document.getElementById('pwd');p.type=p.type==='password'?'text':'password';this.firstElementChild.className=p.type==='password'?'bi bi-eye':'bi bi-eye-slash'"><i class="bi bi-eye"></i></button>
                </div>

                <label class="check"><input type="checkbox" name="remember" value="1"><span></span> Remember me</label>

                <button type="submit" class="btn btn--primary btn--block btn--lg">Sign In <i class="bi bi-arrow-right"></i></button>
                <a href="{{ route('home') }}" class="login__back"><i class="bi bi-arrow-left"></i> Back to website</a>
            </form>
        </div>
    </div>
</body>
</html>
