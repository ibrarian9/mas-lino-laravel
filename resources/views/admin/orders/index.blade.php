@extends('layouts.admin')

@section('page-title', 'Manajemen Pesanan')

@section('content')
<div class="bg-white rounded-xl shadow-sm mb-5">
    <div class="p-4">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[150px]">
                <label class="block font-semibold text-xs mb-1.5 text-primary">Status Pesanan</label>
                <select name="status_pesanan" class="w-full px-3 py-2.5 border-2 border-border rounded-xl font-sans text-sm focus:outline-none focus:border-secondary">
                    <option value="">Semua</option>
                    <option value="baru" {{ request('status_pesanan') === 'baru' ? 'selected' : '' }}>Baru</option>
                    <option value="diproses" {{ request('status_pesanan') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status_pesanan') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status_pesanan') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block font-semibold text-xs mb-1.5 text-primary">Status Pembayaran</label>
                <select name="status_pembayaran" class="w-full px-3 py-2.5 border-2 border-border rounded-xl font-sans text-sm focus:outline-none focus:border-secondary">
                    <option value="">Semua</option>
                    <option value="menunggu" {{ request('status_pembayaran') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="lunas" {{ request('status_pembayaran') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="gagal" {{ request('status_pembayaran') === 'gagal' ? 'selected' : '' }}>Gagal</option>
                </select>
            </div>
            <div class="flex-1 min-w-[150px]">
                <label class="block font-semibold text-xs mb-1.5 text-primary">Tanggal</label>
                <input type="date" name="tanggal" class="w-full px-3 py-2.5 border-2 border-border rounded-xl font-sans text-sm focus:outline-none focus:border-secondary" value="{{ request('tanggal') }}">
            </div>
            <button type="submit" class="h-[42px] px-4 py-2 bg-secondary text-white rounded-xl font-semibold text-sm border-none cursor-pointer hover:opacity-90 flex items-center gap-1">
                <span class="material-symbols-outlined text-lg">filter_alt</span> Filter
            </button>
            <a href="{{ route('admin.orders.index') }}" class="h-[42px] px-4 py-2 border-2 border-secondary text-secondary rounded-xl font-semibold text-sm no-underline flex items-center hover:bg-secondary hover:text-white transition-colors">Reset</a>
        </form>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="flex justify-between items-center px-5 py-3 border-b border-cream">
        <span class="font-semibold text-sm text-primary">Daftar Pesanan</span>
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] font-semibold bg-blue-100 text-blue-800">{{ $pesanan->total() }} total</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-cream/50">
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">No Pesanan</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Meja</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Total</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Metode</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Bayar</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Status</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Waktu</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanan as $p)
                <tr class="border-b border-cream last:border-0 hover:bg-light-bg transition-colors">
                    <td class="px-4 py-3 font-semibold">{{ $p->id_pesanan }}</td>
                    <td class="px-4 py-3"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] font-semibold bg-blue-100 text-blue-800">{{ $p->no_meja }}</span></td>
                    <td class="px-4 py-3 font-semibold">{{ $p->formatted_total }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[0.65rem] font-semibold {{ $p->metode_bayar === 'tunai' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                            <span class="material-symbols-outlined text-[14px]">
                                {{ $p->metode_bayar === 'tunai' ? 'payments' : 'credit_card' }}
                            </span>
                            {{ $p->metode_bayar === 'tunai' ? 'Tunai' : 'Non-Tunai' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @php 
                            $pc = match($p->status_pembayaran) { 
                                'lunas'=>'bg-green-100 text-green-800',
                                'menunggu'=>'bg-yellow-100 text-yellow-800',
                                default=>'bg-red-100 text-red-800' 
                            }; 
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] font-semibold {{ $pc }}">{{ ucfirst($p->status_pembayaran) }}</span>
                    </td>
                    <td class="px-4 py-3">
                        @php 
                            $sc = match($p->status_pesanan) { 
                                'baru'=>'bg-blue-100 text-blue-800',
                                'diproses'=>'bg-yellow-100 text-yellow-800',
                                'selesai'=>'bg-green-100 text-green-800', 
                                default=>'bg-red-100 text-red-800' 
                            }; 
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] font-semibold {{ $sc }}">{{ ucfirst($p->status_pesanan) }}</span>
                    </td>
                    <td class="px-4 py-3 text-xs">{{ $p->waktu_pesan->format('H:i, d/m') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.orders.show', $p->id_pesanan) }}" class="px-3 py-1.5 bg-secondary text-white rounded-lg text-xs font-semibold no-underline hover:opacity-90 inline-flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">visibility</span> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-text-muted py-8">Tidak ada pesanan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4 flex justify-center">{{ $pesanan->withQueryString()->links() }}</div>
@endsection
