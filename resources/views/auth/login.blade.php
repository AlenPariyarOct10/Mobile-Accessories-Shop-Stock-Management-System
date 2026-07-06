<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ $companySetting?->company_name ?? config('app.name') }}</title>
    @if($companySetting?->company_logo)
        <link rel="icon" href="{{ asset('storage/'.$companySetting->company_logo) }}">
        <link rel="apple-touch-icon" href="{{ asset('storage/'.$companySetting->company_logo) }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>{!! file_get_contents(resource_path('css/app.css')) !!}</style>
</head>
<body class="login-page">
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
<div class="card login-card">
    <div class="card-body p-4">
        <div class="d-flex align-items-center gap-3 mb-3">
            @if($companySetting?->company_logo)
                <img class="login-logo" src="{{ asset('storage/'.$companySetting->company_logo) }}" alt="Logo">
            @else
                <span class="login-badge">{{ strtoupper(substr($companySetting?->company_name ?? config('app.name', 'S'), 0, 1)) }}</span>
            @endif
            <div>
                <h1 class="h4 mb-0">{{ $companySetting?->company_name ?? config('app.name', 'Stock Management System') }}</h1>
                <div class="text-muted small">Sign in to continue</div>
            </div>
        </div>
            @include('partials.errors')
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input class="form-control" type="password" name="password" required>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <button class="btn btn-primary w-100" type="submit">Login</button>
                {{-- <p class="small text-muted mt-3 mb-0">Default: admin@example.com / password</p> --}}
            </form>
        </div>
    </div>
</div>
</body>
</html>
