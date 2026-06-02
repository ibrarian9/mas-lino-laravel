@extends('layouts.customer')

@section('title', 'Pembayaran — Es Coklat Mas Lino')

@section('content')
<div class="animate-[fadeIn_0.4s_ease]">
    <h2 class="text-lg font-bold text-primary mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined">receipt</span> Pembayaran
    </h2>

    <div class="bg-white rounded-2xl shadow-sm mb-4">
        <div class="p-4">
            <div class="flex justify-between items-center mb-3">
                <span class="text-xs text-text-muted">No. Pesanan</span>
                <span class="text-sm font-bold text-primary">{{ $pesanan->id_pesanan }}</span>
            </div>
            <div class="flex justify-between items-center mb-3">
                <span class="text-xs text-text-muted">Nomor Meja</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[0.7rem] font-semibold bg-blue-100 text-blue-800">{{ $pesanan->no_meja }}</span>
            </div>
            <div class="flex justify-between items-center mb-3">
                <span class="text-xs text-text-muted">Metode Bayar</span>
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[0.7rem] font-semibold {{ $pesanan->metode_bayar === 'tunai' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                    @if($pesanan->metode_bayar === 'tunai')
                        <span class="material-symbols-outlined text-[1.1rem]">payments</span> Tunai
                    @else
                        <span class="material-symbols-outlined text-[1.1rem]">credit_card</span> Non-Tunai
                    @endif
                </span>
            </div>
            <hr class="border-none border-t border-dashed border-border my-3">
            @foreach($pesanan->details as $detail)
                <div class="flex justify-between items-center py-1.5 text-[0.82rem]">
                    <div>{{ $detail->menu->nama_menu }} <span class="text-text-muted">x{{ $detail->kuantitas }}</span></div>
                    <span class="font-semibold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
            <hr class="border-none border-t-2 border-secondary my-3">
            <div class="flex justify-between items-center">
                <span class="text-base font-bold">Total</span>
                <span class="text-xl font-extrabold text-secondary">{{ $pesanan->formatted_total }}</span>
            </div>
        </div>
    </div>

   @if($pesanan->metode_bayar === 'tunai')
        <div class="bg-white rounded-2xl shadow-sm border-2 border-warning">
            <div class="text-center p-6">
                <div class="mb-3 text-green-600">
                    <span class="material-symbols-outlined text-6xl">payments</span>
                </div>
                <h3 class="text-base mb-2 text-primary font-semibold">Pembayaran Tunai</h3>
                <p class="text-sm text-text-muted mb-4">
                    Silakan bayar <strong class="text-secondary">{{ $pesanan->formatted_total }}</strong> ke kasir.
                </p>
                <div class="inline-flex items-center gap-1 px-4 py-2 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                    <span class="material-symbols-outlined text-base">schedule</span> Menunggu konfirmasi kasir
                </div>
                <div class="mt-5">
                    <a href="{{ route('order.status', $pesanan->id_pesanan) }}" class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-br from-secondary to-secondary-dark text-white rounded-xl font-semibold no-underline">
                        <span class="material-symbols-outlined text-xl">assignment</span> Cek Status Pesanan
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border-2 border-info">
            <div class="text-center p-6">
                <div class="mb-3 text-blue-600">
                    <span class="material-symbols-outlined text-6xl">credit_card</span>
                </div>
                <h3 class="text-base mb-2 text-primary font-semibold">Pembayaran Online</h3>
                @if($snapToken)
                    <p class="text-sm text-text-muted mb-4">Klik tombol di bawah untuk melanjutkan pembayaran.</p>
                    <button class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-br from-secondary to-secondary-dark text-white rounded-xl font-semibold text-base cursor-pointer border-none" id="pay-button">
                        <span class="material-symbols-outlined text-xl">lock</span> Bayar Sekarang — {{ $pesanan->formatted_total }}
                    </button>
                @else
                    <div class="flex items-center gap-2.5 p-3 rounded-xl text-sm bg-yellow-100 text-yellow-800 border-l-4 border-warning text-left">
                        <span class="material-symbols-outlined text-lg">warning</span>
                        Gateway pembayaran belum dikonfigurasi. Silakan hubungi kasir.
                    </div>
                @endif
                <div class="mt-4">
                    <a href="{{ route('order.status', $pesanan->id_pesanan) }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 border-2 border-secondary text-secondary bg-transparent rounded-xl font-semibold no-underline">
                        <span class="material-symbols-outlined text-xl">assignment</span> Cek Status Pesanan
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
@if($pesanan->metode_bayar === 'non_tunai' && $snapToken)
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    document.getElementById('pay-button').addEventListener('click', function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                Swal.fire({
                    icon: 'success',
                    title: 'Pembayaran Berhasil!',
                    text: 'Terima kasih, pesanan kamu sedang diproses.',
                    confirmButtonColor: '#E07B39',
                    customClass: { popup: '!rounded-2xl !font-[Poppins]' }
                }).then(() => {
                    window.location.href = '{{ route("order.status", $pesanan->id_pesanan) }}';
                });
            },
            onPending: function(result) {
                window.location.href = '{{ route("order.status", $pesanan->id_pesanan) }}';
            },
            onError: function(result) {
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran Gagal',
                    text: 'Terjadi kesalahan. Silakan coba lagi.',
                    confirmButtonColor: '#E07B39',
                    customClass: { popup: '!rounded-2xl !font-[Poppins]' }
                });
            },
            onClose: function() {
                showToast('Pembayaran dibatalkan', 'info');
            }
        });
    });
</script>
@endif
@endsection
