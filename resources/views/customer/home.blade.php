@extends('layouts.customer')

@section('title', 'Es Coklat Mas Lino — Selamat Datang')

@section('content')
<div class="text-center pt-8 pb-5">
    <img src="{{ asset('logo.png') }}" class="h-24 w-auto object-contain mx-auto mb-4" alt="Logo">
    <h2 class="text-2xl font-extrabold text-primary mb-1">Es Coklat Mas Lino</h2>
    <p class="text-text-muted text-sm">Jl. Bangau Sakti, Pekanbaru</p>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden mt-6 animate-[fadeIn_0.4s_ease]">
    <div class="p-6">
        <h3 class="text-lg font-semibold mb-1 text-primary">Masukkan Nomor Meja</h3>
        <p class="text-xs text-text-muted mb-5">Silakan masukkan nomor meja Anda untuk mulai memesan.</p>

        <form action="{{ route('customer.setMeja') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="no_meja" class="flex items-center gap-1 font-semibold text-sm mb-1.5 text-primary">
                    <span class="material-symbols-outlined text-lg">chair</span> Nomor Meja
                </label>
                <input type="text" name="no_meja" id="no_meja"
                       class="w-full px-4 py-3 border-2 border-border rounded-xl font-sans text-xl text-center font-semibold tracking-widest transition-colors focus:outline-none focus:border-secondary focus:ring-3 focus:ring-secondary/15 bg-white"
                       placeholder="Contoh: A1, B2, 3..."
                       value="{{ old('no_meja') }}"
                       required maxlength="10">
                @error('no_meja')
                    <small class="text-danger text-xs">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-br from-secondary to-secondary-dark text-white rounded-xl font-semibold text-base cursor-pointer transition-all hover:opacity-90 hover:-translate-y-0.5 mt-2">
                <span class="material-symbols-outlined text-xl">restaurant</span> Mulai Pesan
            </button>
        </form>
    </div>
</div>

<div class="text-center mt-8 text-text-muted">
    <p class="text-xs">Scan QR-Code di meja untuk mulai</p>
    <p class="text-[0.7rem] mt-1">© {{ date('Y') }} Es Coklat Mas Lino</p>
</div>
@endsection
