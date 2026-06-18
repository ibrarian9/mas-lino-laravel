@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-blue-100 text-info flex items-center justify-center"><span class="material-symbols-outlined text-2xl">receipt_long</span></div>
        <div>
            <h3 class="text-2xl font-bold">{{ $totalPesananHariIni }}</h3>
            <p class="text-xs text-text-muted">Pesanan Hari Ini</p>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-green-100 text-success flex items-center justify-center"><span class="material-symbols-outlined text-2xl">payments</span></div>
        <div>
            <h3 class="text-2xl font-bold">Rp {{ number_format($totalPendapatanHariIni, 0, ',', '.') }}</h3>
            <p class="text-xs text-text-muted">Pendapatan Hari Ini</p>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-orange-100 text-secondary flex items-center justify-center"><span class="material-symbols-outlined text-2xl">hourglass_top</span></div>
        <div>
            <h3 class="text-2xl font-bold">{{ $pesananPerStatus['baru'] }}</h3>
            <p class="text-xs text-text-muted">Pesanan Baru</p>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm flex items-center gap-3">
        <div class="w-12 h-12 rounded-xl bg-red-100 text-danger flex items-center justify-center"><span class="material-symbols-outlined text-2xl">local_fire_department</span></div>
        <div>
            <h3 class="text-2xl font-bold">{{ $pesananPerStatus['diproses'] }}</h3>
            <p class="text-xs text-text-muted">Sedang Diproses</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden lg:col-span-2">
        <div class="flex justify-between items-center px-5 py-3 border-b border-cream">
            <span class="font-semibold text-sm text-primary flex items-center gap-1"><span class="material-symbols-outlined text-lg">schedule</span> Pesanan Terbaru</span>
            <a href="{{ route('admin.orders.index') }}" class="text-xs px-3 py-1.5 border border-secondary text-secondary rounded-lg no-underline hover:bg-secondary hover:text-white transition-colors">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-cream/50">
                        <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">No Pesanan</th>
                        <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Meja</th>
                        <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Total</th>
                        <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Bayar</th>
                        <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesananTerbaru as $p)
                    <tr class="border-b border-cream last:border-0 cursor-pointer hover:bg-light-bg transition-colors" onclick="window.location='{{ route('admin.orders.show', $p->id_pesanan) }}'">
                        <td class="px-4 py-3 font-semibold text-xs">{{ $p->id_pesanan }}</td>
                        <td class="px-4 py-3"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] font-semibold bg-blue-100 text-blue-800">{{ $p->no_meja }}</span></td>
                        <td class="px-4 py-3 font-semibold">{{ $p->formatted_total }}</td>
                        <td class="px-4 py-3">
                            @php $pc = match($p->status_pembayaran) { 'lunas'=>'bg-green-100 text-green-800','menunggu'=>'bg-yellow-100 text-yellow-800',default=>'bg-red-100 text-red-800' }; @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] font-semibold {{ $pc }}">{{ ucfirst($p->status_pembayaran) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @php $sc = match($p->status_pesanan) { 'baru'=>'bg-blue-100 text-blue-800','diproses'=>'bg-yellow-100 text-yellow-800','selesai'=>'bg-green-100 text-green-800',default=>'bg-red-100 text-red-800' }; @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] font-semibold {{ $sc }}">{{ ucfirst($p->status_pesanan) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-text-muted py-8">Belum ada pesanan hari ini</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm">
        <div class="px-5 py-3 border-b border-cream">
            <span class="font-semibold text-sm text-primary flex items-center gap-1"><span class="material-symbols-outlined text-lg">emoji_events</span> Top Bundling</span>
        </div>
        <div class="p-4">
            @forelse($topBundles as $idx => $bundle)
                <div class="flex items-center gap-3 py-2.5 {{ !$loop->last ? 'border-b border-cream' : '' }}">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-white text-sm
                        {{ $idx === 0 ? 'bg-gradient-to-r from-yellow-400 to-orange-400 !text-primary-dark' : '' }}
                        {{ $idx === 1 ? 'bg-gradient-to-r from-gray-300 to-gray-400' : '' }}
                        {{ $idx === 2 ? 'bg-gradient-to-r from-amber-600 to-amber-700' : '' }}
                        {{ $idx > 2 ? 'bg-cream !text-text-muted' : '' }}">{{ $idx+1 }}</div>
                    <div class="flex-1">
                        <div class="text-[0.82rem] font-semibold">{{ $bundle->nama_menu }}</div>
                        <div class="text-[0.72rem] text-text-muted">{{ $bundle->total_order_c2 }} terjual</div>
                    </div>
                </div>
            @empty
                <div class="text-center text-text-muted py-5"><p class="text-sm">Belum ada data penjualan bundling</p></div>
            @endforelse
        </div>

        <!-- Reset Data Button -->
        <div class="px-4 pb-4 pt-2 border-t border-cream">
            <form id="reset-data-form" action="{{ route('admin.resetData') }}" method="POST">
                @csrf
            </form>
            <button type="button" onclick="confirmReset()" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-50 hover:bg-red-100 text-danger border border-red-200 rounded-xl text-xs font-semibold cursor-pointer transition-colors">
                <span class="material-symbols-outlined text-base">delete_sweep</span>
                Reset Data Pesanan
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
setInterval(() => {
    fetch('{{ route("admin.orders.pesananBaru") }}')
        .then(r => r.json())
        .then(data => { document.title = data.count > 0 ? '(' + data.count + ') Dashboard' : 'Dashboard — Es Coklat Mas Lino'; });
}, 15000);

function confirmReset() {
    Swal.fire({
        title: 'Reset Semua Data?',
        html: '<div style="text-align:left; font-size:0.85rem; color:#5A6E8A;">' +
              '<p>Ini akan menghapus <strong>semua</strong>:</p>' +
              '<ul style="margin:8px 0; padding-left:20px;">' +
              '<li>Data Pesanan</li>' +
              '<li>Detail Pesanan</li>' +
              '<li>Rating & Ulasan</li>' +
              '<li>Statistik Menu (total order, rating)</li>' +
              '</ul>' +
              '<p style="color:#E74C3C; font-weight:600;">Aksi ini tidak bisa dibatalkan!</p></div>',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#E74C3C',
        cancelButtonColor: '#5A6E8A',
        confirmButtonText: 'Ya, Reset Semua',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            popup: '!rounded-2xl !font-[Poppins]',
            title: '!text-lg !font-bold !text-[#1A2744]',
            confirmButton: '!rounded-xl !font-semibold !px-6',
            cancelButton: '!rounded-xl !font-semibold !px-6'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('reset-data-form').submit();
        }
    });
}
</script>
@endsection
