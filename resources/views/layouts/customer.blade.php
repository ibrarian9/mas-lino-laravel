<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Es Coklat Mas Lino')</title>
    <meta name="description" content="Es Coklat Mas Lino — Pesan minuman coklat favorit kamu langsung dari meja!">
    <link rel="preload" href="{{ asset('fonts/material-symbols-outlined.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-light-bg text-text-dark min-h-screen pb-24 antialiased">

    <!-- Top Header -->
    <div class="bg-gradient-to-br from-primary to-primary-dark text-white px-5 py-4 sticky top-0 z-50 shadow-lg">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-lg font-bold tracking-tight">🍫 Es Coklat Mas Lino</h1>
                <div class="text-[0.7rem] opacity-80 font-light mt-0.5">Jl. Bangau Sakti, Pekanbaru</div>
            </div>
            @if(session('no_meja'))
                <div class="bg-accent text-primary-dark px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1">
                    <span class="material-symbols-outlined text-base">chair</span> Meja {{ session('no_meja') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <div class="px-4 py-4 max-w-md mx-auto">
        @if(session('success'))
            <div class="flex items-center gap-2.5 p-3 rounded-xl mb-4 text-sm bg-green-100 text-green-800 border-l-4 border-success animate-[slideDown_0.3s_ease]">
                <span class="material-symbols-outlined text-lg">check_circle</span> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flex items-center gap-2.5 p-3 rounded-xl mb-4 text-sm bg-red-100 text-red-800 border-l-4 border-danger animate-[slideDown_0.3s_ease]">
                <span class="material-symbols-outlined text-lg">error</span> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Bottom Navigation -->
    @if(session('no_meja'))
    <nav class="fixed bottom-0 left-0 right-0 bg-white h-[70px] flex justify-around items-center shadow-[0_-4px_20px_rgba(0,0,0,0.08)] z-[1000] border-t border-border">
        <a href="{{ route('customer.menu') }}" class="flex flex-col items-center gap-0.5 no-underline text-text-muted text-[0.65rem] font-medium px-4 py-2 rounded-xl transition-all relative {{ request()->routeIs('customer.menu') ? '!text-secondary' : '' }}">
            <span class="material-symbols-outlined text-2xl">restaurant</span>
            <span>Menu</span>
            @if(request()->routeIs('customer.menu'))<span class="absolute -bottom-0.5 w-5 h-[3px] bg-secondary rounded-full"></span>@endif
        </a>
        <a href="{{ route('cart.index') }}" class="flex flex-col items-center gap-0.5 no-underline text-text-muted text-[0.65rem] font-medium px-4 py-2 rounded-xl transition-all relative {{ request()->routeIs('cart.index') ? '!text-secondary' : '' }}">
            <span class="material-symbols-outlined text-2xl">shopping_cart</span>
            <span>Keranjang</span>
            <span class="absolute top-0.5 right-2 bg-danger text-white text-[0.6rem] w-[18px] h-[18px] rounded-full flex items-center justify-center font-bold" id="cart-badge" style="{{ ($cartCount ?? 0) > 0 ? '' : 'display:none' }}">{{ $cartCount ?? 0 }}</span>
            @if(request()->routeIs('cart.index'))<span class="absolute -bottom-0.5 w-5 h-[3px] bg-secondary rounded-full"></span>@endif
        </a>
        @php $lastOrderId = session('last_order_id'); @endphp
        <a href="{{ $lastOrderId ? route('order.status', $lastOrderId) : '#' }}"
           class="flex flex-col items-center gap-0.5 no-underline text-text-muted text-[0.65rem] font-medium px-4 py-2 rounded-xl transition-all relative {{ request()->routeIs('order.status') ? '!text-secondary' : '' }}"
           id="nav-status"
           @if(!$lastOrderId) onclick="event.preventDefault(); showToast('Belum ada pesanan aktif', 'info');" @endif>
            <span class="material-symbols-outlined text-2xl">assignment</span>
            <span>Status</span>
            @if(request()->routeIs('order.status'))<span class="absolute -bottom-0.5 w-5 h-[3px] bg-secondary rounded-full"></span>@endif
        </a>
    </nav>
    @endif

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'bottom',
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            customClass: { popup: '!rounded-full !px-5 !py-2 !text-sm !font-medium' },
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        function showToast(message, icon = 'success') {
            Toast.fire({ icon: icon, title: message });
        }

        function confirmAction(options) {
            Swal.fire({
                title: options.title || 'Konfirmasi',
                text: options.text || 'Apakah kamu yakin?',
                icon: options.icon || 'question',
                showCancelButton: true,
                confirmButtonColor: '#E07B39',
                cancelButtonColor: '#8B7355',
                confirmButtonText: options.confirmText || 'Ya, lanjutkan',
                cancelButtonText: options.cancelText || 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: '!rounded-2xl !font-[Poppins]',
                    title: '!text-lg !font-bold !text-[#2C1810]',
                    htmlContainer: '!text-sm !text-[#8B7355]',
                    confirmButton: '!rounded-xl !font-semibold !px-6',
                    cancelButton: '!rounded-xl !font-semibold !px-6'
                }
            }).then((result) => {
                if (result.isConfirmed && options.onConfirm) options.onConfirm();
            });
        }

        function formatRupiah(num) {
            return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>

    @yield('scripts')
</body>
</html>
