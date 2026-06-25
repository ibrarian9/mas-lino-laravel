<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — Es Coklat Mas Lino')</title>
    <link rel="preload" href="{{ asset('fonts/material-symbols-outlined.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="icon" href="{{ asset('logo-xs.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-cream text-text-dark min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-[260px] bg-gradient-to-b from-primary to-primary-dark text-white fixed top-0 left-0 h-screen z-[1000] overflow-y-auto transition-transform max-md:-translate-x-full" id="sidebar">
        <div class="px-5 py-6 border-b border-white/10 flex items-center gap-3">
            <img src="{{ asset('logo-xs.png') }}" width="40" height="40" class="h-10 w-10 object-contain rounded-lg bg-white p-0.5 shrink-0" alt="Logo" decoding="async">
            <div>
                <h2 class="text-[1rem] font-bold leading-tight">Es Coklat Mas Lino</h2>
                <small class="text-[0.65rem] opacity-70">{{ Auth::guard('admin')->user()->isManajemen() ? 'Admin Manajemen' : 'Admin Kasir' }}</small>
            </div>
        </div>
        <nav class="p-3">
            <div class="text-[0.65rem] uppercase tracking-[1.5px] opacity-50 px-3 pt-3 pb-1.5">UTAMA</div>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-white/75 no-underline rounded-xl text-[0.85rem] font-medium transition-all mb-0.5 hover:bg-white/10 hover:text-white {{ request()->routeIs('admin.dashboard') ? '!bg-secondary !text-white' : '' }}">
                <span class="material-symbols-outlined text-xl w-5 text-center">pie_chart</span> Dashboard
            </a>

            <div class="text-[0.65rem] uppercase tracking-[1.5px] opacity-50 px-3 pt-3 pb-1.5">KELOLA</div>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-white/75 no-underline rounded-xl text-[0.85rem] font-medium transition-all mb-0.5 hover:bg-white/10 hover:text-white {{ request()->routeIs('admin.orders.*') ? '!bg-secondary !text-white' : '' }}">
                <span class="material-symbols-outlined text-xl w-5 text-center">receipt_long</span> Pesanan
            </a>
            <a href="{{ route('admin.menu.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-white/75 no-underline rounded-xl text-[0.85rem] font-medium transition-all mb-0.5 hover:bg-white/10 hover:text-white {{ request()->routeIs('admin.menu.*') ? '!bg-secondary !text-white' : '' }}">
                <span class="material-symbols-outlined text-xl w-5 text-center">coffee</span> Menu
            </a>

            @if(Auth::guard('admin')->user()->isManajemen())
            <div class="text-[0.65rem] uppercase tracking-[1.5px] opacity-50 px-3 pt-3 pb-1.5">ALAT</div>
            <a href="{{ route('admin.qrcode') }}" class="flex items-center gap-3 px-4 py-2.5 text-white/75 no-underline rounded-xl text-[0.85rem] font-medium transition-all mb-0.5 hover:bg-white/10 hover:text-white {{ request()->routeIs('admin.qrcode') ? '!bg-secondary !text-white' : '' }}">
                <span class="material-symbols-outlined text-xl w-5 text-center">qr_code</span> QR Code
            </a>
            <a href="{{ route('admin.saw.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-white/75 no-underline rounded-xl text-[0.85rem] font-medium transition-all mb-0.5 hover:bg-white/10 hover:text-white {{ request()->routeIs('admin.saw.*') ? '!bg-secondary !text-white' : '' }}">
                <span class="material-symbols-outlined text-xl w-5 text-center">analytics</span> Laporan SAW
            </a>
            <a href="{{ route('admin.sales.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-white/75 no-underline rounded-xl text-[0.85rem] font-medium transition-all mb-0.5 hover:bg-white/10 hover:text-white {{ request()->routeIs('admin.sales.*') ? '!bg-secondary !text-white' : '' }}">
                <span class="material-symbols-outlined text-xl w-5 text-center">bar_chart</span> Laporan Penjualan
            </a>
            @endif

            <div class="text-[0.65rem] uppercase tracking-[1.5px] opacity-50 px-3 pt-3 pb-1.5">AKUN</div>
            <form action="{{ route('admin.logout') }}" method="POST" class="m-0" id="logout-form">
                @csrf
                <button type="button" onclick="confirmLogout()" class="flex items-center gap-3 px-4 py-2.5 text-white/75 rounded-xl text-[0.85rem] font-medium transition-all mb-0.5 hover:bg-white/10 hover:text-white w-full border-none bg-transparent cursor-pointer text-left">
                    <span class="material-symbols-outlined text-xl w-5 text-center">logout</span> Logout
                </button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="ml-[260px] flex-1 min-h-screen max-md:ml-0">
        <header class="bg-white px-7 py-4 flex justify-between items-center shadow-sm sticky top-0 z-[100]">
            <div class="flex items-center gap-3">
                <button onclick="document.getElementById('sidebar').classList.toggle('max-md:-translate-x-full')" class="hidden max-md:block btn border-none px-3 py-2" id="menu-toggle">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <h1 class="text-xl font-bold text-primary">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-4">
                <div class="w-9 h-9 bg-secondary rounded-full flex items-center justify-center text-white font-bold text-[0.85rem]">
                    {{ strtoupper(substr(Auth::guard('admin')->user()->username ?? 'A', 0, 1)) }}
                </div>
            </div>
        </header>

        <div class="p-6">
            @if(session('success'))
                <div class="flex items-center gap-2.5 p-3 rounded-lg mb-4 text-sm bg-green-100 text-green-800 border-l-4 border-success">
                    <span class="material-symbols-outlined text-lg">check_circle</span> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-2.5 p-3 rounded-lg mb-4 text-sm bg-red-100 text-red-800 border-l-4 border-danger">
                    <span class="material-symbols-outlined text-lg">error</span> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        function formatRupiah(num) {
            return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function confirmAction(options) {
            Swal.fire({
                title: options.title || 'Konfirmasi',
                text: options.text || 'Apakah kamu yakin?',
                icon: options.icon || 'question',
                showCancelButton: true,
                confirmButtonColor: '#D42426',
                cancelButtonColor: '#5A6E8A',
                confirmButtonText: options.confirmText || 'Ya, lanjutkan',
                cancelButtonText: options.cancelText || 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: '!rounded-2xl !font-[Poppins]',
                    title: '!text-lg !font-bold !text-[#1A2744]',
                    htmlContainer: '!text-sm !text-[#5A6E8A]',
                    confirmButton: '!rounded-xl !font-semibold !px-6',
                    cancelButton: '!rounded-xl !font-semibold !px-6'
                }
            }).then((result) => {
                if (result.isConfirmed && options.onConfirm) options.onConfirm();
            });
        }

        function confirmLogout() {
            confirmAction({
                title: 'Logout',
                text: 'Yakin ingin keluar dari panel admin?',
                icon: 'warning',
                confirmText: 'Ya, logout',
                onConfirm: () => document.getElementById('logout-form').submit()
            });
        }
    </script>

    @yield('scripts')
</body>
</html>
