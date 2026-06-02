<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — Es Coklat Mas Lino')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans bg-cream text-text-dark min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-[260px] bg-gradient-to-b from-primary to-primary-dark text-white fixed top-0 left-0 h-screen z-[1000] overflow-y-auto transition-transform max-md:-translate-x-full" id="sidebar">
        <div class="px-5 py-6 border-b border-white/10">
            <h2 class="text-[1.1rem] font-bold">🍫 Es Coklat Mas Lino</h2>
            <small class="text-[0.7rem] opacity-70">Panel Admin / Kasir</small>
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

            <div class="text-[0.65rem] uppercase tracking-[1.5px] opacity-50 px-3 pt-3 pb-1.5">ALAT</div>
            <a href="{{ route('admin.qrcode') }}" class="flex items-center gap-3 px-4 py-2.5 text-white/75 no-underline rounded-xl text-[0.85rem] font-medium transition-all mb-0.5 hover:bg-white/10 hover:text-white {{ request()->routeIs('admin.qrcode') ? '!bg-secondary !text-white' : '' }}">
                <span class="material-symbols-outlined text-xl w-5 text-center">qr_code</span> QR Code
            </a>

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
