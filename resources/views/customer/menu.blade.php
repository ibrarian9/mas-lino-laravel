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

<!-- Rekomendasi SAW (Horizontal Carousel — always visible) -->
@if(!empty($rekomendasi))
<div class="mb-5">
    <div class="flex items-center gap-2 mb-3">
        <span class="material-symbols-outlined text-accent text-xl" style="font-variation-settings: 'FILL' 1;">star</span>
        <h3 class="text-sm font-bold text-primary">Rekomendasi Untukmu</h3>
        <span class="text-[0.65rem] bg-accent/20 text-accent px-2 py-0.5 rounded-full font-semibold">SAW</span>
    </div>
    <div class="relative -mx-4">
        <div id="rekomendasi-carousel" class="flex gap-3 overflow-x-auto pb-3 px-4 snap-x snap-mandatory scrollbar-hide cursor-grab active:cursor-grabbing" style="-webkit-overflow-scrolling: touch;">
        @foreach($rekomendasi as $idx => $reko)
        <div class="snap-start shrink-0 w-[160px] bg-white rounded-2xl overflow-hidden shadow-md relative {{ $idx === 0 ? 'ring-2 ring-accent' : '' }}">
            <!-- Rank Badge -->
            <span class="absolute top-2 left-2 z-10 w-6 h-6 rounded-full flex items-center justify-center text-[0.65rem] font-bold
                {{ $idx === 0 ? 'bg-gradient-to-br from-yellow-400 to-orange-400 text-primary-dark' : '' }}
                {{ $idx === 1 ? 'bg-gradient-to-br from-gray-300 to-gray-400 text-white' : '' }}
                {{ $idx === 2 ? 'bg-gradient-to-br from-amber-600 to-amber-700 text-white' : '' }}
                {{ $idx > 2 ? 'bg-primary/10 text-primary' : '' }}">
                {{ $idx + 1 }}
            </span>
            <!-- Image -->
            <div class="w-full h-[100px] bg-gradient-to-br from-border to-light-bg flex items-center justify-center text-3xl">
                @if($reko['gambar'])
                    <img src="{{ asset('storage/' . $reko['gambar']) }}" alt="{{ $reko['nama'] }}" class="w-full h-full object-contain">
                @else
                    📦
                @endif
            </div>
            <!-- Info -->
            <div class="px-2.5 py-2">
                <div class="text-[0.72rem] font-semibold text-text-dark leading-tight mb-1 line-clamp-2 h-[2.2em]">{{ $reko['nama'] }}</div>
                <div class="text-xs font-bold text-secondary mb-0.5">Rp {{ number_format($reko['harga'], 0, ',', '.') }}</div>
                <div class="text-[0.6rem] text-text-muted flex items-center gap-0.5">
                    <span class="material-symbols-outlined text-[0.6rem] text-accent" style="font-variation-settings: 'FILL' 1;">star</span>
                    {{ round($reko['menu']->rating_rata_rata_c3) }}
                </div>
            </div>
            <!-- Add Button -->
            <button class="w-full py-2 border-none bg-gradient-to-r from-secondary to-secondary-dark text-white font-semibold text-[0.7rem] cursor-pointer transition-opacity active:opacity-80 min-h-[36px] flex items-center justify-center gap-1" onclick="addToCart({{ $reko['id'] }})">
                <span class="material-symbols-outlined text-sm">add_shopping_cart</span> Tambah
            </button>
        </div>
        @endforeach
        </div>
    </div>
</div>
@endif

<!-- Tabs: Reguler & Bundling -->
<div class="flex gap-2 mb-4 overflow-x-auto pb-1">
    <button class="menu-tab px-5 py-2 rounded-full text-xs font-semibold cursor-pointer border-2 border-border bg-white text-text-muted whitespace-nowrap transition-all min-h-[44px] active" onclick="switchTab('reguler', this)">🥤 Reguler</button>
    <button class="menu-tab px-5 py-2 rounded-full text-xs font-semibold cursor-pointer border-2 border-border bg-white text-text-muted whitespace-nowrap transition-all min-h-[44px]" onclick="switchTab('bundling', this)">📦 Bundling</button>
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
                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_menu }}" class="w-full h-full object-contain">
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
                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_menu }}" class="w-full h-full object-contain">
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

// Desktop drag-to-scroll for recommendation carousel
(function() {
    const el = document.getElementById('rekomendasi-carousel');
    if (!el) return;
    let isDown = false, startX, scrollLeft;

    el.addEventListener('mousedown', (e) => {
        isDown = true;
        startX = e.pageX - el.offsetLeft;
        scrollLeft = el.scrollLeft;
    });
    el.addEventListener('mouseleave', () => isDown = false);
    el.addEventListener('mouseup', () => isDown = false);
    el.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - el.offsetLeft;
        el.scrollLeft = scrollLeft - (x - startX);
    });

    // Mouse wheel horizontal scroll
    el.addEventListener('wheel', (e) => {
        if (Math.abs(e.deltaY) > 0) {
            e.preventDefault();
            el.scrollLeft += e.deltaY;
        }
    }, { passive: false });
})();
</script>
@endsection
