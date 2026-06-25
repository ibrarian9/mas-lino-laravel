@extends('layouts.admin')

@section('page-title', 'Edit Menu')

@section('content')
<a href="{{ route('admin.menu.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-secondary text-secondary rounded-lg text-xs font-semibold no-underline hover:bg-secondary hover:text-white transition-colors mb-5">
    <span class="material-symbols-outlined text-base">arrow_back</span> Kembali
</a>

<div class="bg-white rounded-xl shadow-sm max-w-[600px]">
    <div class="px-5 py-3 border-b border-cream font-semibold text-sm text-primary">Edit: {{ $menu->nama_menu }}</div>
    <div class="p-5">
        <form action="{{ route('admin.menu.update', $menu->id_menu) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="nama_menu" class="block font-semibold text-sm mb-1.5 text-primary">Nama Menu</label>
                <input type="text" name="nama_menu" id="nama_menu" class="w-full px-4 py-3 border-2 border-border rounded-xl font-sans text-base focus:outline-none focus:border-secondary" value="{{ old('nama_menu', $menu->nama_menu) }}" required maxlength="150">
                @error('nama_menu') <small class="text-danger text-xs">{{ $message }}</small> @enderror
            </div>
            <div class="mb-4">
                <label for="kategori" class="block font-semibold text-sm mb-1.5 text-primary">Kategori</label>
                <select name="kategori" id="kategori" class="w-full px-4 py-3 border-2 border-border rounded-xl font-sans text-base focus:outline-none focus:border-secondary" required>
                    <option value="reguler" {{ old('kategori', $menu->kategori) === 'reguler' ? 'selected' : '' }}>Reguler</option>
                    <option value="bundling" {{ old('kategori', $menu->kategori) === 'bundling' ? 'selected' : '' }}>Bundling</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="deskripsi" class="block font-semibold text-sm mb-1.5 text-primary">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" class="w-full px-4 py-3 border-2 border-border rounded-xl font-sans text-base focus:outline-none focus:border-secondary resize-none" rows="3">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
            </div>
            <div class="mb-4">
                <label for="gambar" class="block font-semibold text-sm mb-1.5 text-primary">Gambar</label>
                @if($menu->gambar)
                    <div class="mb-2"><img src="{{ asset('storage/' . $menu->gambar) }}" class="w-24 h-24 object-cover rounded-lg" loading="lazy" decoding="async"></div>
                @endif
                <input type="file" name="gambar" id="gambar" class="w-full px-4 py-3 border-2 border-border rounded-xl text-sm" accept="image/*">
                <small class="text-text-muted text-xs">Kosongkan jika tidak ingin mengganti gambar</small>
                @error('gambar') <small class="text-danger text-xs">{{ $message }}</small> @enderror
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="mb-4">
                    <label for="harga_normal" class="block font-semibold text-sm mb-1.5 text-primary">Harga Normal (Rp)</label>
                    <input type="number" name="harga_normal" id="harga_normal" class="w-full px-4 py-3 border-2 border-border rounded-xl font-sans text-base focus:outline-none focus:border-secondary" value="{{ old('harga_normal', $menu->harga_normal) }}" required min="0">
                    @error('harga_normal') <small class="text-danger text-xs">{{ $message }}</small> @enderror
                </div>
                <div class="mb-4">
                    <label for="diskon" class="block font-semibold text-sm mb-1.5 text-primary">Diskon (Rp)</label>
                    <input type="number" name="diskon" id="diskon" class="w-full px-4 py-3 border-2 border-border rounded-xl font-sans text-base focus:outline-none focus:border-secondary" value="{{ old('diskon', $menu->diskon) }}" min="0">
                </div>
            </div>
            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-br from-secondary to-secondary-dark text-white rounded-xl font-semibold border-none cursor-pointer mt-2 hover:opacity-90 flex items-center justify-center gap-1">
                <span class="material-symbols-outlined text-xl">save</span> Update Menu
            </button>
        </form>
    </div>
</div>
@endsection
