@extends('layouts.admin')

@section('page-title', 'Laporan SAW')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6">
    <div>
        <h3 class="text-base font-semibold">Analisis Simple Additive Weighting (SAW)</h3>
        <p class="text-xs text-text-muted mt-0.5">Perhitungan rekomendasi paket bundling berdasarkan metode SAW</p>
    </div>
    <a href="{{ route('admin.saw.export') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-success text-white rounded-xl font-semibold text-sm no-underline hover:opacity-90 transition-opacity">
        <span class="material-symbols-outlined text-lg">download</span> Export Excel
    </a>
</div>

@if(empty($raw))
    <div class="bg-white rounded-xl shadow-sm text-center py-16">
        <span class="material-symbols-outlined text-5xl text-text-muted mb-3">analytics</span>
        <h4 class="text-base text-text-muted mb-1">Belum Ada Data</h4>
        <p class="text-xs text-text-muted">Tambahkan paket bundling aktif untuk melihat analisis SAW.</p>
    </div>
@else

<!-- Bobot Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    @foreach($bobot as $key => $b)
    <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 {{ $key === 'c1' ? 'border-danger' : ($key === 'c2' ? 'border-info' : 'border-accent') }}">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-semibold text-text-muted uppercase tracking-wide">{{ strtoupper($key) }}</span>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[0.65rem] font-semibold {{ $b['tipe'] === 'Cost' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">{{ $b['tipe'] }}</span>
        </div>
        <div class="text-lg font-bold text-primary">{{ $b['nama'] }}</div>
        <div class="text-2xl font-extrabold text-secondary mt-1">{{ $b['nilai'] }}</div>
    </div>
    @endforeach
</div>

<!-- Table 1: Data Kriteria -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-5 py-3 border-b border-cream flex items-center gap-2">
        <span class="material-symbols-outlined text-lg text-primary">table_chart</span>
        <span class="font-semibold text-sm text-primary">Tabel 1 — Data Kriteria (Alternatif)</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-cream/50">
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">No</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Nama Menu</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-right">C1 (Harga)</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-right">C2 (Popularitas)</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-right">C3 (Rating)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($raw as $idx => $item)
                <tr class="border-b border-cream last:border-0">
                    <td class="px-4 py-2.5 text-text-muted">{{ $idx + 1 }}</td>
                    <td class="px-4 py-2.5 font-semibold">{{ $item['nama'] }}</td>
                    <td class="px-4 py-2.5 text-right">Rp {{ number_format($item['c1'], 0, ',', '.') }}</td>
                    <td class="px-4 py-2.5 text-right">{{ $item['c2'] }}</td>
                    <td class="px-4 py-2.5 text-right">{{ number_format($item['c3'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-cream/30">
                    <td colspan="2" class="px-4 py-2.5 text-xs font-semibold text-text-muted">Referensi</td>
                    <td class="px-4 py-2.5 text-right text-xs font-semibold text-danger">Min: Rp {{ number_format($minMax['c1']['value'], 0, ',', '.') }}</td>
                    <td class="px-4 py-2.5 text-right text-xs font-semibold text-info">Max: {{ $minMax['c2']['value'] }}</td>
                    <td class="px-4 py-2.5 text-right text-xs font-semibold text-accent">Max: {{ number_format($minMax['c3']['value'], 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Table 2: Normalisasi -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-5 py-3 border-b border-cream flex items-center gap-2">
        <span class="material-symbols-outlined text-lg text-primary">functions</span>
        <span class="font-semibold text-sm text-primary">Tabel 2 — Matriks Normalisasi</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-cream/50">
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">No</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Nama Menu</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-right">R1 (Harga)</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-right">R2 (Popularitas)</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-right">R3 (Rating)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($normalized as $idx => $item)
                <tr class="border-b border-cream last:border-0">
                    <td class="px-4 py-2.5 text-text-muted">{{ $idx + 1 }}</td>
                    <td class="px-4 py-2.5 font-semibold">{{ $item['nama'] }}</td>
                    <td class="px-4 py-2.5 text-right font-mono text-sm">{{ number_format($item['r1'], 4) }}</td>
                    <td class="px-4 py-2.5 text-right font-mono text-sm">{{ number_format($item['r2'], 4) }}</td>
                    <td class="px-4 py-2.5 text-right font-mono text-sm">{{ number_format($item['r3'], 4) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 bg-cream/30 text-[0.72rem] text-text-muted space-y-0.5">
        <div><strong>R1</strong> = Min(C1) / C1 &nbsp;→&nbsp; <em>Cost (semakin kecil semakin baik)</em></div>
        <div><strong>R2</strong> = C2 / Max(C2) &nbsp;→&nbsp; <em>Benefit (semakin besar semakin baik)</em></div>
        <div><strong>R3</strong> = C3 / Max(C3) &nbsp;→&nbsp; <em>Benefit (semakin besar semakin baik)</em></div>
    </div>
</div>

<!-- Table 3: Preferensi & Ranking -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-5 py-3 border-b border-cream flex items-center gap-2">
        <span class="material-symbols-outlined text-lg text-primary">leaderboard</span>
        <span class="font-semibold text-sm text-primary">Tabel 3 — Nilai Preferensi & Ranking</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="bg-cream/50">
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Rank</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide">Nama Menu</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-right">Vi</th>
                    <th class="px-4 py-3 text-xs font-semibold text-text-muted uppercase tracking-wide text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ranking as $idx => $item)
                <tr class="border-b border-cream last:border-0 {{ $idx === 0 ? 'bg-accent/5' : '' }}">
                    <td class="px-4 py-2.5">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-xs font-bold
                            {{ $idx === 0 ? 'bg-gradient-to-br from-yellow-400 to-orange-400 text-primary-dark' : '' }}
                            {{ $idx === 1 ? 'bg-gradient-to-br from-gray-300 to-gray-400 text-white' : '' }}
                            {{ $idx === 2 ? 'bg-gradient-to-br from-amber-600 to-amber-700 text-white' : '' }}
                            {{ $idx > 2 ? 'bg-cream text-text-muted' : '' }}">
                            {{ $item['rank'] }}
                        </span>
                    </td>
                    <td class="px-4 py-2.5 font-semibold {{ $idx === 0 ? 'text-secondary' : '' }}">
                        {{ $item['nama'] }}
                        @if($idx === 0)
                            <span class="material-symbols-outlined text-accent text-sm align-middle ml-1" style="font-variation-settings: 'FILL' 1;">emoji_events</span>
                        @endif
                    </td>
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
        <strong>Vi</strong> = ({{ $bobot['c1']['nilai'] }} × R1) + ({{ $bobot['c2']['nilai'] }} × R2) + ({{ $bobot['c3']['nilai'] }} × R3)
    </div>
</div>

@endif
@endsection
