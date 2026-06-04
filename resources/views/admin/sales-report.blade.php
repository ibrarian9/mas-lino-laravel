@extends('layouts.admin')

@section('page-title', 'Laporan Penjualan')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
    <div>
        <h3 class="text-base font-semibold">Laporan Penjualan & Rekomendasi Menu</h3>
        <p class="text-xs text-text-muted mt-0.5">Ringkasan penjualan bulanan dan rekomendasi SAW</p>
    </div>
    <div class="flex items-center gap-2">
        <form method="GET" class="flex items-center gap-2">
            <select name="year" onchange="this.form.submit()" class="px-3 py-2 border-2 border-border rounded-xl text-sm bg-white focus:outline-none focus:border-secondary cursor-pointer">
                @foreach($availableYears as $y)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('admin.sales.export', ['year' => $year]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-success text-white rounded-xl font-semibold text-sm no-underline hover:opacity-90 transition-opacity">
            <span class="material-symbols-outlined text-lg">download</span> Export Excel
        </a>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-success">
        <div class="text-xs font-semibold text-text-muted uppercase tracking-wide mb-1">Total Pendapatan</div>
        <div class="text-2xl font-extrabold text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        <div class="text-xs text-text-muted mt-1">Tahun {{ $year }}</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-info">
        <div class="text-xs font-semibold text-text-muted uppercase tracking-wide mb-1">Total Pesanan</div>
        <div class="text-2xl font-extrabold text-info">{{ number_format($totalPesanan) }}</div>
        <div class="text-xs text-text-muted mt-1">Semua status</div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-secondary">
        <div class="text-xs font-semibold text-text-muted uppercase tracking-wide mb-1">Pesanan Lunas</div>
        <div class="text-2xl font-extrabold text-secondary">{{ number_format($totalLunas) }}</div>
        <div class="text-xs text-text-muted mt-1">{{ $totalPesanan > 0 ? round(($totalLunas / $totalPesanan) * 100) : 0 }}% dari total</div>
    </div>
</div>

<!-- Table 1: Penjualan Bulanan -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-5 py-3 border-b border-cream flex items-center gap-2">
        <span class="material-symbols-outlined text-lg text-primary">calendar_month</span>
        <span class="font-semibold text-sm text-primary">Penjualan Per Bulan — {{ $year }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-cream/50">
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Bulan</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-right">Total Pesanan</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-right">Lunas</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-right">Menunggu</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-right">Gagal</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-right">Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($months as $m)
                <tr class="border-b border-cream last:border-0 {{ $m['total_pesanan'] > 0 ? '' : 'opacity-40' }}">
                    <td class="px-4 py-2.5 font-semibold">{{ $m['nama_bulan'] }}</td>
                    <td class="px-4 py-2.5 text-right">{{ $m['total_pesanan'] }}</td>
                    <td class="px-4 py-2.5 text-right text-success font-semibold">{{ $m['pesanan_lunas'] }}</td>
                    <td class="px-4 py-2.5 text-right text-warning">{{ $m['pesanan_menunggu'] }}</td>
                    <td class="px-4 py-2.5 text-right text-danger">{{ $m['pesanan_gagal'] }}</td>
                    <td class="px-4 py-2.5 text-right font-semibold {{ $m['total_pendapatan'] > 0 ? 'text-secondary' : '' }}">
                        Rp {{ number_format($m['total_pendapatan'], 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-cream/50 font-bold">
                    <td class="px-4 py-3">TOTAL</td>
                    <td class="px-4 py-3 text-right">{{ $totalPesanan }}</td>
                    <td class="px-4 py-3 text-right text-success">{{ $totalLunas }}</td>
                    <td class="px-4 py-3 text-right text-warning">{{ array_sum(array_column($months, 'pesanan_menunggu')) }}</td>
                    <td class="px-4 py-3 text-right text-danger">{{ array_sum(array_column($months, 'pesanan_gagal')) }}</td>
                    <td class="px-4 py-3 text-right text-secondary">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Table 2: Menu Terlaris -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-5 py-3 border-b border-cream flex items-center gap-2">
        <span class="material-symbols-outlined text-lg text-primary">trending_up</span>
        <span class="font-semibold text-sm text-primary">Menu Terlaris — {{ $year }}</span>
    </div>
    @if($topMenus->isEmpty())
        <div class="text-center py-10 text-text-muted">
            <span class="material-symbols-outlined text-4xl mb-2">info</span>
            <p class="text-sm">Belum ada data penjualan menu.</p>
        </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-cream/50">
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">No</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Nama Menu</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Kategori</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-right">Harga</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-right">Terjual</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-right">Revenue</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topMenus as $idx => $menu)
                <tr class="border-b border-cream last:border-0 {{ $idx < 3 ? 'bg-accent/5' : '' }}">
                    <td class="px-4 py-2.5">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold
                            {{ $idx === 0 ? 'bg-gradient-to-br from-yellow-400 to-orange-400 text-primary-dark' : '' }}
                            {{ $idx === 1 ? 'bg-gradient-to-br from-gray-300 to-gray-400 text-white' : '' }}
                            {{ $idx === 2 ? 'bg-gradient-to-br from-amber-600 to-amber-700 text-white' : '' }}
                            {{ $idx > 2 ? 'bg-cream text-text-muted' : '' }}">
                            {{ $idx + 1 }}
                        </span>
                    </td>
                    <td class="px-4 py-2.5 font-semibold {{ $idx === 0 ? 'text-secondary' : '' }}">
                        {{ $menu->nama_menu }}
                        @if($idx === 0)
                            <span class="material-symbols-outlined text-accent text-sm align-middle ml-1" style="font-variation-settings: 'FILL' 1;">emoji_events</span>
                        @endif
                    </td>
                    <td class="px-4 py-2.5">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] font-semibold {{ $menu->kategori === 'bundling' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">{{ ucfirst($menu->kategori) }}</span>
                    </td>
                    <td class="px-4 py-2.5 text-right">Rp {{ number_format($menu->harga_c1, 0, ',', '.') }}</td>
                    <td class="px-4 py-2.5 text-right font-semibold">{{ $menu->total_terjual }}</td>
                    <td class="px-4 py-2.5 text-right font-semibold text-secondary">Rp {{ number_format($menu->total_revenue, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

<!-- Table 3: Rekomendasi SAW -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-5 py-3 border-b border-cream flex items-center gap-2">
        <span class="material-symbols-outlined text-lg text-primary">auto_awesome</span>
        <span class="font-semibold text-sm text-primary">Rekomendasi Menu (SAW)</span>
    </div>
    @if(empty($sawRanking))
        <div class="text-center py-10 text-text-muted">
            <span class="material-symbols-outlined text-4xl mb-2">info</span>
            <p class="text-sm">Belum ada data rekomendasi SAW.</p>
        </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-cream/50">
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Rank</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Nama Menu</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-right">Harga</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-right">R1</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-right">R2</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-right">R3</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-right">Skor Vi</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sawRanking as $idx => $item)
                <tr class="border-b border-cream last:border-0 {{ $idx < 3 ? 'bg-accent/5' : '' }}">
                    <td class="px-4 py-2.5">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full text-xs font-bold
                            {{ $idx === 0 ? 'bg-gradient-to-br from-yellow-400 to-orange-400 text-primary-dark' : '' }}
                            {{ $idx === 1 ? 'bg-gradient-to-br from-gray-300 to-gray-400 text-white' : '' }}
                            {{ $idx === 2 ? 'bg-gradient-to-br from-amber-600 to-amber-700 text-white' : '' }}
                            {{ $idx > 2 ? 'bg-cream text-text-muted' : '' }}">
                            {{ $idx + 1 }}
                        </span>
                    </td>
                    <td class="px-4 py-2.5 font-semibold {{ $idx === 0 ? 'text-secondary' : '' }}">
                        {{ $item['nama'] }}
                        @if($idx === 0)
                            <span class="material-symbols-outlined text-accent text-sm align-middle ml-1" style="font-variation-settings: 'FILL' 1;">emoji_events</span>
                        @endif
                    </td>
                    <td class="px-4 py-2.5 text-right">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                    <td class="px-4 py-2.5 text-right font-mono text-sm">{{ number_format($item['r1'], 4) }}</td>
                    <td class="px-4 py-2.5 text-right font-mono text-sm">{{ number_format($item['r2'], 4) }}</td>
                    <td class="px-4 py-2.5 text-right font-mono text-sm">{{ number_format($item['r3'], 4) }}</td>
                    <td class="px-4 py-2.5 text-right font-mono text-sm font-bold {{ $idx === 0 ? 'text-secondary' : '' }}">{{ number_format($item['vi'], 4) }}</td>
                    <td class="px-4 py-2.5 text-center">
                        @if($idx < 3)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] font-semibold bg-green-100 text-green-800">Direkomendasikan</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] font-semibold bg-gray-100 text-gray-600">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 bg-cream/30 text-[0.72rem] text-text-muted">
        <strong>Vi</strong> = (0.30 × R1) + (0.25 × R2) + (0.45 × R3) &nbsp;|&nbsp;
        <strong>R1</strong> = Min(C1)/C1 (Cost) &nbsp;|&nbsp;
        <strong>R2</strong> = C2/Max(C2) (Benefit) &nbsp;|&nbsp;
        <strong>R3</strong> = C3/Max(C3) (Benefit)
    </div>
    @endif
</div>
@endsection
