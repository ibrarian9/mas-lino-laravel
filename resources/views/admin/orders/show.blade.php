@extends('layouts.admin')

@section('page-title', 'Detail Pesanan')

@section('content')
<a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-secondary text-secondary rounded-lg text-xs font-semibold no-underline hover:bg-secondary hover:text-white transition-colors mb-5">
    <span class="material-symbols-outlined text-base">arrow_back</span> Kembali
</a>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <div class="bg-white rounded-xl shadow-sm lg:col-span-2">
        <div class="flex justify-between items-center px-5 py-3 border-b border-cream">
            <span class="font-semibold text-sm text-primary">{{ $pesanan->id_pesanan }}</span>
            @php $sc = match($pesanan->status_pesanan) { 'baru'=>'bg-blue-100 text-blue-800','diproses'=>'bg-yellow-100 text-yellow-800','selesai'=>'bg-green-100 text-green-800',default=>'bg-red-100 text-red-800' }; @endphp
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[0.7rem] font-semibold {{ $sc }}">{{ ucfirst($pesanan->status_pesanan) }}</span>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-2 gap-3 mb-5">
                <div><div class="text-xs text-text-muted">Nomor Meja</div><div class="font-semibold text-base">{{ $pesanan->no_meja }}</div></div>
                <div><div class="text-xs text-text-muted">Waktu Pesan</div><div class="font-medium text-sm">{{ $pesanan->waktu_pesan->format('H:i, d M Y') }}</div></div>
                <div>
                    <div class="text-xs text-text-muted mb-1">Metode Bayar</div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[0.7rem] font-semibold {{ $pesanan->metode_bayar === 'tunai' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                            <span class="material-symbols-outlined text-[14px]">
                                {{ $pesanan->metode_bayar === 'tunai' ? 'payments' : 'credit_card' }}
                            </span>
                            {{ $pesanan->metode_bayar === 'tunai' ? 'Tunai' : 'Non-Tunai' }}
                        </span>
                    </div>
                <div>
                    <div class="text-xs text-text-muted">Status Pembayaran</div>
                    @php $pc = match($pesanan->status_pembayaran) { 'lunas'=>'bg-green-100 text-green-800','menunggu'=>'bg-yellow-100 text-yellow-800',default=>'bg-red-100 text-red-800' }; @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[0.7rem] font-semibold {{ $pc }}">{{ ucfirst($pesanan->status_pembayaran) }}</span>
                </div>
            </div>

            <h4 class="text-sm font-semibold mb-3 text-primary">Item Pesanan</h4>
            <table class="w-full text-left text-sm">
                <thead><tr class="bg-cream/50">
                    <th class="px-4 py-2.5 text-xs font-semibold text-text-muted uppercase tracking-wide">Menu</th>
                    <th class="px-4 py-2.5 text-xs font-semibold text-text-muted uppercase tracking-wide">Harga</th>
                    <th class="px-4 py-2.5 text-xs font-semibold text-text-muted uppercase tracking-wide">Qty</th>
                    <th class="px-4 py-2.5 text-xs font-semibold text-text-muted uppercase tracking-wide">Subtotal</th>
                </tr></thead>
                <tbody>
                    @foreach($pesanan->details as $detail)
                    <tr class="border-b border-cream last:border-0">
                        <td class="px-4 py-2.5">{{ $detail->menu->nama_menu }}</td>
                        <td class="px-4 py-2.5">Rp {{ number_format($detail->menu->harga_c1, 0, ',', '.') }}</td>
                        <td class="px-4 py-2.5">{{ $detail->kuantitas }}</td>
                        <td class="px-4 py-2.5 font-semibold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot><tr class="bg-cream/50">
                    <td colspan="3" class="px-4 py-3 font-bold text-right">Total</td>
                    <td class="px-4 py-3 font-bold text-secondary text-lg">{{ $pesanan->formatted_total }}</td>
                </tr></tfoot>
            </table>
        </div>
    </div>

    <div class="space-y-4">
        @if($pesanan->metode_bayar === 'tunai' && $pesanan->status_pembayaran === 'menunggu')
        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-5 py-3 border-b border-cream font-semibold text-sm text-primary">💵 Validasi Pembayaran Tunai</div>
            <div class="p-4">
                <p class="text-[0.82rem] text-text-muted mb-3">Konfirmasi pembayaran tunai <strong class="text-secondary">{{ $pesanan->formatted_total }}</strong></p>
                <form action="{{ route('admin.orders.validateCash', $pesanan->id_pesanan) }}" method="POST" id="validate-cash-form">
                    @csrf
                    <button type="button" onclick="confirmValidateCash()" class="w-full px-4 py-2.5 bg-success text-white rounded-xl font-semibold text-sm border-none cursor-pointer hover:opacity-90 flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-lg">check</span> Validasi Lunas
                    </button>
                </form>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm">
            <div class="px-5 py-3 border-b border-cream font-semibold text-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">assignment</span>
                Update Status Pesanan
            </div>
            <div class="p-4">
                <form action="{{ route('admin.orders.updateStatus', $pesanan->id_pesanan) }}" method="POST" id="update-status-form">
                    @csrf
                    <div class="mb-3">
                        <select name="status" id="status-select" class="w-full px-3 py-2.5 border-2 border-border rounded-xl font-sans text-sm focus:outline-none focus:border-secondary" required>
                            <option value="baru" {{ $pesanan->status_pesanan === 'baru' ? 'selected' : '' }}>Baru</option>
                            <option value="diproses" {{ $pesanan->status_pesanan === 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ $pesanan->status_pesanan === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ $pesanan->status_pesanan === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <button type="button" onclick="confirmUpdateStatus()" class="w-full px-4 py-2.5 bg-secondary text-white rounded-xl font-semibold text-sm border-none cursor-pointer hover:opacity-90 flex items-center justify-center gap-1">
                        <span class="material-symbols-outlined text-lg">sync</span> Update Status
                    </button>
                </form>
            </div>
        </div>

        @if($pesanan->admin)
        <div class="bg-white rounded-xl shadow-sm p-4 text-[0.82rem] flex items-center gap-1">
            <span class="material-symbols-outlined text-lg">admin_panel_settings</span> Dikelola oleh: <strong>{{ $pesanan->admin->username }}</strong>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmValidateCash() {
    confirmAction({
        title: 'Validasi Pembayaran',
        text: 'Yakin pembayaran tunai sudah diterima?',
        icon: 'question',
        confirmText: 'Ya, validasi lunas',
        onConfirm: () => document.getElementById('validate-cash-form').submit()
    });
}

function confirmUpdateStatus() {
    const status = document.getElementById('status-select');
    const selectedText = status.options[status.selectedIndex].text;
    confirmAction({
        title: 'Update Status',
        text: 'Ubah status pesanan menjadi "' + selectedText + '"?',
        icon: 'question',
        confirmText: 'Ya, update',
        onConfirm: () => document.getElementById('update-status-form').submit()
    });
}
</script>
@endsection
