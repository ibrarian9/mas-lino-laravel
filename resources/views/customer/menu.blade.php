@extends('layouts.customer')

@section('title', 'Menu — Es Coklat Mas Lino')

@section('content')
<!-- Search -->
<div class="relative mb-4">
    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-text-muted text-xl">search</span>
    <input type="text" id="searchInput" placeholder="Cari menu..."
           class="w-full pl-10 pr-4 py-2.5 border-2 border-border rounded-full font-sans text-sm bg-white focus:outline-none focus:border-secondary"
           oninput="filterMenu()">
</div>

<!-- Tabs -->
<div class="flex gap-2 mb-5 overflow-x-auto pb-1">
    <button class="menu-tab px-5 py-2 rounded-full text-xs font-semibold cursor-pointer border-2 border-border bg-white text-text-muted whitespace-nowrap transition-all min-h-[44px] active" onclick="switchTab('reguler', this)">🥤 Reguler</button>
    <button class="menu-tab px-5 py-2 rounded-full text-xs font-semibold cursor-pointer border-2 border-border bg-white text-text-muted whitespace-nowrap transition-all min-h-[44px]" onclick="switchTab('bundling', this)">📦 Bundling</button>
    <button class="menu-tab px-5 py-2 rounded-full text-xs font-semibold cursor-pointer border-2 border-border bg-white text-text-muted whitespace-nowrap transition-all min-h-[44px]" onclick="switchTab('rekomendasi', this)">⭐ Rekomendasi</button>
</div>

<!-- Tab: Reguler -->
<div class="tab-content" id="tab-reguler">
    @if($reguler->isEmpty())
        <div class="text-center py-10 text-text-muted">
            <span class="material-symbols-outlined text-4xl mb-2">coffee</span>
            <p>Belum ada menu reguler.</p>
        </div>
    @else
        <div class="grid grid-cols-2 gap-3">
            @foreach($reguler as $item)
                <div class="menu-item bg-white rounded-2xl overflow-hidden shadow-sm transition-transform active:scale-[0.98] relative" data-name="{{ strtolower($item->nama_menu) }}">
                    <div class="w-full h-[120px] bg-gradient-to-br from-border to-light-bg flex items-center justify-center text-4xl">
                        @if($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_menu }}" class="w-full h-full object-cover">
                        @else
                            🥤
                        @endif
                    </div>
                    <div class="px-3 py-2.5">
                        <div class="text-[0.78rem] font-semibold text-text-dark leading-tight mb-1 line-clamp-2">{{ $item->nama_menu }}</div>
                        <div class="text-sm font-bold text-secondary">
                            {{ $item->formatted_harga }}
                            @if($item->diskon > 0)
                                <span class="text-[0.7rem] text-text-muted line-through ml-1">{{ $item->formatted_harga_normal }}</span>
                            @endif
                        </div>
                    </div>
                    <button class="w-full py-2 border-none bg-gradient-to-r from-secondary to-secondary-dark text-white font-semibold text-xs cursor-pointer transition-opacity active:opacity-80 min-h-[44px] flex items-center justify-center gap-1" onclick="addToCart({{ $item->id_menu }})">
                        <span class="material-symbols-outlined text-base">add</span> Tambah
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Tab: Bundling -->
<div class="tab-content hidden" id="tab-bundling">
    @if($bundling->isEmpty())
        <div class="text-center py-10 text-text-muted">
            <span class="material-symbols-outlined text-4xl mb-2">inventory_2</span>
            <p>Belum ada paket bundling.</p>
        </div>
    @else
        <div class="grid grid-cols-2 gap-3">
            @foreach($bundling as $item)
                <div class="menu-item bg-white rounded-2xl overflow-hidden shadow-sm transition-transform active:scale-[0.98] relative" data-name="{{ strtolower($item->nama_menu) }}">
                    <div class="w-full h-[120px] bg-gradient-to-br from-border to-light-bg flex items-center justify-center text-4xl">
                        @if($item->gambar)
                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_menu }}" class="w-full h-full object-cover">
                        @else
                            📦
                        @endif
                    </div>
                    <div class="px-3 py-2.5">
                        <div class="text-[0.78rem] font-semibold text-text-dark leading-tight mb-1 line-clamp-2">{{ $item->nama_menu }}</div>
                        <div class="text-sm font-bold text-secondary">
                            {{ $item->formatted_harga }}
                            @if($item->diskon > 0)
                                <span class="text-[0.7rem] text-text-muted line-through ml-1">{{ $item->formatted_harga_normal }}</span>
                            @endif
                        </div>
                        <div class="mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="material-symbols-outlined text-[0.7rem] {{ $i <= round($item->rating_rata_rata_c3) ? 'text-accent' : 'text-border' }}" style="font-variation-settings: 'FILL' 1;">star</span>
                            @endfor
                            <span class="text-[0.65rem] text-text-muted ml-0.5">({{ $item->jumlah_rating }})</span>
                        </div>
                    </div>
                    <button class="w-full py-2 border-none bg-gradient-to-r from-secondary to-secondary-dark text-white font-semibold text-xs cursor-pointer transition-opacity active:opacity-80 min-h-[44px] flex items-center justify-center gap-1" onclick="addToCart({{ $item->id_menu }})">
                        <span class="material-symbols-outlined text-base">add</span> Tambah
                    </button>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Tab: Rekomendasi -->
<div class="tab-content hidden" id="tab-rekomendasi">
    <div class="mb-4">
        <h3 class="text-base font-bold text-primary">📊 Rekomendasi SAW</h3>
        <p class="text-xs text-text-muted">Paket terbaik berdasarkan harga, popularitas, dan rating</p>
    </div>

    @if(empty($rekomendasi))
        <div class="text-center py-10 text-text-muted">
            <p>Belum ada data rekomendasi.</p>
        </div>
    @else
        @foreach($rekomendasi as $idx => $reko)
            <div class="bg-white rounded-2xl overflow-hidden shadow-md mb-3 relative {{ $idx === 0 ? 'border-2 border-accent' : '' }}">
                <span class="absolute top-2 left-2 z-10 px-3 py-1 rounded-full text-[0.7rem] font-bold
                    {{ $idx === 0 ? 'bg-gradient-to-r from-yellow-400 to-orange-400 text-primary-dark' : '' }}
                    {{ $idx === 1 ? 'bg-gradient-to-r from-gray-300 to-gray-400 text-white' : '' }}
                    {{ $idx === 2 ? 'bg-gradient-to-r from-amber-600 to-amber-700 text-white' : '' }}">
                    #{{ $idx + 1 }}
                </span>
                <div class="flex items-center gap-3 p-3.5">
                    <div class="w-[70px] h-[70px] rounded-xl bg-gradient-to-br from-border to-light-bg flex items-center justify-center text-3xl shrink-0 overflow-hidden">
                        @if($reko['gambar'])
                            <img src="{{ asset('storage/' . $reko['gambar']) }}" class="w-full h-full object-cover rounded-xl">
                        @else
                            📦
                        @endif
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold mb-0.5">{{ $reko['nama'] }}</h4>
                        <div class="text-sm font-bold text-secondary">Rp {{ number_format($reko['harga'], 0, ',', '.') }}</div>
                        <div class="text-[0.7rem] text-text-muted">Skor SAW: {{ number_format($reko['vi'], 4) }}</div>
                    </div>
                    <div class="flex flex-col items-end gap-1.5">
                        <button class="px-3 py-2 bg-secondary text-white rounded-lg text-xs font-semibold cursor-pointer border-none hover:opacity-90 flex items-center gap-1" onclick="addToCart({{ $reko['id'] }})">
                            <span class="material-symbols-outlined text-lg">add_shopping_cart</span>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection

@section('scripts')
<script>
function switchTab(tabName, el) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.add('hidden'));
    document.querySelectorAll('.menu-tab').forEach(t => {
        t.classList.remove('active', '!bg-secondary', '!text-white', '!border-secondary');
    });
    document.getElementById('tab-' + tabName).classList.remove('hidden');
    el.classList.add('active', '!bg-secondary', '!text-white', '!border-secondary');
}

document.querySelector('.menu-tab.active')?.classList.add('!bg-secondary', '!text-white', '!border-secondary');

function addToCart(idMenu) {
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ id_menu: idMenu, qty: 1 })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('cart-badge').textContent = data.cartCount;
            document.getElementById('cart-badge').style.display = data.cartCount > 0 ? 'flex' : 'none';
            showToast(data.message);
        }
    })
    .catch(err => showToast('Gagal menambahkan item', 'error'));
}

function filterMenu() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    document.querySelectorAll('.menu-item').forEach(item => {
        item.style.display = item.dataset.name.includes(q) ? '' : 'none';
    });
}
</script>
@endsection
