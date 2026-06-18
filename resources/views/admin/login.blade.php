<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Es Coklat Mas Lino</title>
    <link rel="preload" href="{{ asset('fonts/material-symbols-outlined.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="icon" href="{{ asset('logo.png') }}" type="image/png">
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans min-h-screen flex items-center justify-center bg-gradient-to-br from-primary via-primary-dark to-text-dark p-5">
    <div class="bg-white rounded-2xl px-8 py-10 w-full max-w-[400px] shadow-[0_20px_60px_rgba(0,0,0,0.3)]">
        <div class="text-center mb-8">
            <img src="{{ asset('logo.png') }}" class="h-20 w-auto object-contain mx-auto mb-3" alt="Logo">
            <h1 class="text-xl font-bold text-primary">Es Coklat Mas Lino</h1>
            <p class="text-xs text-text-muted">Login Panel Admin / Kasir</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 text-red-800 px-4 py-2.5 rounded-lg text-[0.82rem] mb-4 border-l-4 border-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login') }}" method="POST">
            @csrf
            <div class="mb-5">
                <label for="username" class="block font-semibold text-sm mb-1.5 text-primary">Username</label>
                <input type="text" name="username" id="username"
                       class="w-full px-4 py-3 border-2 border-border rounded-xl font-sans text-base transition-colors focus:outline-none focus:border-secondary focus:ring-3 focus:ring-secondary/15"
                       value="{{ old('username') }}" placeholder="Masukkan username" required autofocus>
            </div>
            <div class="mb-5">
                <label for="password" class="block font-semibold text-sm mb-1.5 text-primary">Password</label>
                <input type="password" name="password" id="password"
                       class="w-full px-4 py-3 border-2 border-border rounded-xl font-sans text-base transition-colors focus:outline-none focus:border-secondary focus:ring-3 focus:ring-secondary/15"
                       placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="w-full py-3 bg-gradient-to-br from-secondary to-secondary-dark text-white rounded-xl font-sans text-base font-semibold cursor-pointer transition-all hover:opacity-90 hover:-translate-y-0.5 border-none">
                Masuk
            </button>
        </form>
    </div>
</body>
</html>
