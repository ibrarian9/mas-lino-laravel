@extends('layouts.admin')

@section('page-title', 'Manajemen Menu')

@section('content')
<div class="flex justify-between items-center mb-5">
    <h3 class="text-base font-semibold">Daftar Menu & Paket Bundling</h3>
    <a href="{{ route('admin.menu.create') }}" class="px-4 py-2 bg-secondary text-white rounded-xl font-semibold text-sm no-underline hover:opacity-90 inline-flex items-center gap-1">
        <span class="material-symbols-outlined text-lg">add</span> Tambah Menu
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-cream/50">
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Gambar</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Nama Menu</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Kategori</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Harga Normal</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Diskon</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Harga Akhir</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Rating</th>

                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Status</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menus as $menu)
                <tr class="border-b border-cream last:border-0 {{ !$menu->is_active ? 'opacity-50' : '' }}">
                    <td class="px-4 py-3">
                        <div class="w-12 h-12 rounded-lg bg-cream flex items-center justify-center text-xl overflow-hidden">
                            @if($menu->gambar)
                                <img src="{{ asset('storage/' . $menu->gambar) }}" class="w-full h-full object-cover">
                            @else
                                {{ $menu->is_bundle ? '📦' : '🥤' }}
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3 font-semibold">{{ $menu->nama_menu }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] font-semibold {{ $menu->kategori === 'bundling' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">{{ ucfirst($menu->kategori) }}</span>
                    </td>
                    <td class="px-4 py-3">{{ $menu->formatted_harga_normal }}</td>
                    <td class="px-4 py-3">{{ $menu->diskon > 0 ? 'Rp '.number_format($menu->diskon, 0, ',', '.') : '-' }}</td>
                    <td class="px-4 py-3 font-semibold text-secondary">{{ $menu->formatted_harga }}</td>
                    <td class="px-4 py-3">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="material-symbols-outlined text-[0.7rem] {{ $i <= round($menu->rating_rata_rata_c3) ? 'text-accent' : 'text-border' }}" style="font-variation-settings: 'FILL' 1;">star</span>
                        @endfor
                        <br><small class="text-text-muted">{{ $menu->jumlah_rating }} ulasan</small>
                    </td>

                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] font-semibold {{ $menu->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $menu->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-1">
                            <a href="{{ route('admin.menu.edit', $menu->id_menu) }}" class="px-2 py-1.5 bg-info text-white rounded-lg text-xs no-underline hover:opacity-90 inline-flex items-center">
                                <span class="material-symbols-outlined text-base">edit</span>
                            </a>
                            <form action="{{ route('admin.menu.toggleActive', $menu->id_menu) }}" method="POST" class="inline toggle-form">
                                @csrf
                                <button type="button" onclick="confirmToggle(this, '{{ $menu->nama_menu }}', {{ $menu->is_active ? 'true' : 'false' }})" class="px-2 py-1.5 rounded-lg text-xs border-none cursor-pointer text-white inline-flex items-center {{ $menu->is_active ? 'bg-warning' : 'bg-success' }}">
                                    <span class="material-symbols-outlined text-base">{{ $menu->is_active ? 'visibility_off' : 'visibility' }}</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center text-text-muted py-8">Belum ada menu</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmToggle(btn, menuName, isActive) {
    const action = isActive ? 'nonaktifkan' : 'aktifkan';
    confirmAction({
        title: (isActive ? 'Nonaktifkan' : 'Aktifkan') + ' Menu',
        text: 'Yakin ingin ' + action + ' "' + menuName + '"?',
        icon: 'warning',
        confirmText: 'Ya, ' + action,
        onConfirm: () => btn.closest('form').submit()
    });
}
</script>
@endsection
