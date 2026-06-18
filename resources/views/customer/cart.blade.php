@extends('layouts.customer')

@section('title', 'Keranjang — Es Coklat Mas Lino')

@section('content')
<h2 class="text-lg font-bold text-primary mb-4 flex items-center gap-2">
    <span class="material-symbols-outlined">shopping_cart</span> Keranjang
</h2>

@if(empty($cart))
    <div class="bg-white rounded-2xl shadow-sm text-center px-6 py-12">
        <div class="text-5xl mb-3">🛒</div>
        <h3 class="text-base text-text-muted mb-2">Keranjang masih kosong</h3>
        <p class="text-xs text-text-muted mb-5">Yuk, pilih menu favoritmu!</p>
        <a href="{{ route('customer.menu') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-br from-secondary to-secondary-dark text-white rounded-xl font-semibold no-underline">
            <span class="material-symbols-outlined text-xl">restaurant</span> Lihat Menu
        </a>
    </div>
@else
    <div id="cart-items">
        @foreach($cart as $key => $item)
            <div class="bg-white rounded-2xl shadow-sm mb-3" id="cart-{{ $item['id_menu'] }}">
                <div class="flex items-center gap-3 p-3.5">
                    <div class="w-14 h-14 rounded-xl bg-white flex items-center justify-center text-2xl shrink-0 overflow-hidden">
                        @if($item['gambar'])
                            <img src="{{ asset('storage/' . $item['gambar']) }}" class="w-full h-full object-contain rounded-xl">
                        @else
                            🍫
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-semibold mb-0.5">{{ $item['nama'] }}</div>
                        <div class="text-xs font-bold text-secondary">Rp {{ number_format($item['harga'], 0, ',', '.') }}</div>
                        <div class="text-xs text-text-muted" id="subtotal-{{ $item['id_menu'] }}">
                            Subtotal: Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="flex flex-col items-center gap-1.5">
                        <div class="flex items-center gap-2">
                            <button class="w-8 h-8 rounded-full border-2 border-secondary text-secondary bg-transparent flex items-center justify-center cursor-pointer" onclick="updateQty({{ $item['id_menu'] }}, -1)">
                                <span class="material-symbols-outlined text-base">remove</span>
                            </button>
                            <span class="font-bold text-base min-w-[24px] text-center" id="qty-{{ $item['id_menu'] }}">{{ $item['qty'] }}</span>
                            <button class="w-8 h-8 rounded-full bg-secondary text-white border-none flex items-center justify-center cursor-pointer" onclick="updateQty({{ $item['id_menu'] }}, 1)">
                                <span class="material-symbols-outlined text-base">add</span>
                            </button>
                        </div>
                        <button class="bg-transparent border-none text-danger text-[0.7rem] cursor-pointer p-1 flex items-center gap-0.5" onclick="removeItem({{ $item['id_menu'] }})">
                            <span class="material-symbols-outlined text-sm">delete</span> Hapus
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Total & Checkout -->
    <div class="bg-white rounded-2xl shadow-sm mt-4 border-2 border-secondary">
        <div class="p-4">
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm font-semibold">Total Pembayaran</span>
                <span class="text-xl font-bold text-secondary" id="total-price">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </span>
            </div>
            <a href="{{ route('customer.menu') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 border-2 border-secondary text-secondary bg-transparent rounded-xl font-semibold no-underline mb-2">
                <span class="material-symbols-outlined text-xl">add</span> Tambah Menu Lain
            </a>
            <form action="{{ route('checkout') }}" method="POST" id="checkout-form">
                @csrf
                <div class="mb-3">
                    <label class="block font-semibold text-sm mb-1.5 text-primary">Metode Pembayaran</label>
                    <select name="metode_bayar" class="w-full px-4 py-3 border-2 border-border rounded-xl font-sans text-base transition-colors focus:outline-none focus:border-secondary" required>
                        <option value="">Pilih metode...</option>
                        <option value="tunai">Tunai — Bayar di Kasir</option>
                        <option value="non_tunai">Non-Tunai — Midtrans</option>
                    </select>
                </div>
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-br from-success to-[#219a52] text-white rounded-xl font-semibold cursor-pointer border-none">
                    <span class="material-symbols-outlined text-xl">check_circle</span> Konfirmasi Pesanan
                </button>
            </form>
        </div>
    </div>
@endif
@endsection

@section('scripts')
<script>
document.getElementById('checkout-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const metode = form.querySelector('[name=metode_bayar]');
    if (!metode.value) { metode.reportValidity(); return; }

    const metodeText = metode.options[metode.selectedIndex].text;
    confirmAction({
        title: 'Konfirmasi Pesanan',
        text: 'Pesan dengan metode ' + metodeText + '?',
        icon: 'question',
        confirmText: 'Ya, pesan sekarang',
        onConfirm: () => form.submit()
    });
});

function updateQty(idMenu, delta) {
    const qtyEl = document.getElementById('qty-' + idMenu);
    let newQty = parseInt(qtyEl.textContent) + delta;
    if (newQty < 1) { removeItem(idMenu); return; }

    fetch('{{ route("cart.update") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        body: JSON.stringify({ id_menu: idMenu, qty: newQty })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            qtyEl.textContent = newQty;
            document.getElementById('subtotal-' + idMenu).textContent = 'Subtotal: ' + formatRupiah(data.item_subtotal);
            document.getElementById('total-price').textContent = formatRupiah(data.total);
            document.getElementById('cart-badge').textContent = data.cartCount;
        }
    });
}

function removeItem(idMenu) {
    confirmAction({
        title: 'Hapus Item',
        text: 'Yakin ingin menghapus item ini dari keranjang?',
        icon: 'warning',
        confirmText: 'Ya, hapus',
        onConfirm: () => {
            fetch('{{ route("cart.remove") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ id_menu: idMenu })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const el = document.getElementById('cart-' + idMenu);
                    if (el) el.remove();
                    document.getElementById('total-price').textContent = formatRupiah(data.total);
                    document.getElementById('cart-badge').textContent = data.cartCount;
                    if (data.cartCount === 0) location.reload();
                    showToast('Item berhasil dihapus');
                }
            });
        }
    });
}
</script>
@endsection
