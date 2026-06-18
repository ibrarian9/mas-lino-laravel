@extends('layouts.customer')

@section('title', 'Status Pesanan — Es Coklat Mas Lino')

@section('content')
<div class="animate-[fadeIn_0.4s_ease]">
    <div class="text-center mb-5">
        <h2 class="text-lg font-bold text-primary">Status Pesanan</h2>
        <p class="text-xs text-text-muted">{{ $pesanan->id_pesanan }}</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm mb-4">
        <div class="p-4 text-center">
            <span class="text-xs text-text-muted">Status Pembayaran</span>
            <div class="mt-1.5">
                @php
                    $payClass = match($pesanan->status_pembayaran) {
                        'lunas' => 'bg-green-100 text-green-800',
                        'menunggu' => 'bg-yellow-100 text-yellow-800',
                        default => 'bg-red-100 text-red-800',
                    };
                    $payIcon = match($pesanan->status_pembayaran) {
                        'lunas' => 'check_circle',
                        'menunggu' => 'schedule',
                        default => 'cancel',
                    };
                @endphp
                <span class="inline-flex items-center gap-1 px-4 py-1.5 rounded-full text-sm font-semibold {{ $payClass }}" id="payment-badge">
                    <span class="material-symbols-outlined text-lg">{{ $payIcon }}</span> {{ ucfirst($pesanan->status_pembayaran) }}
                </span>
            </div>
        </div>
    </div>

    @php
        $statuses = ['baru', 'diproses', 'selesai'];
        $currentIdx = array_search($pesanan->status_pesanan, $statuses);
        if ($currentIdx === false) $currentIdx = -1;
        $icons = ['description', 'local_fire_department', 'done_all'];
        $labels = ['Baru', 'Diproses', 'Selesai'];
    @endphp

    <div class="bg-white rounded-2xl shadow-sm mb-4">
        <div class="p-4">
            <div class="flex justify-between relative mx-2 my-6">
                <div class="absolute top-5 left-10 right-10 h-[3px] bg-border z-0"></div>
                @foreach($statuses as $idx => $status)
                    @php
                        $dotBase = 'w-10 h-10 rounded-full flex items-center justify-center mb-2 transition-all z-10';
                        $labelBase = 'text-[0.7rem] font-semibold text-center';
                        if ($idx < $currentIdx) {
                            $dotCls = "$dotBase bg-success text-white shadow-[0_0_0_6px_rgba(39,174,96,0.15)]";
                            $labelCls = "$labelBase text-success";
                        } elseif ($idx == $currentIdx) {
                            $dotCls = "$dotBase bg-secondary text-white animate-pulse-dot shadow-[0_0_0_6px_rgba(212,36,38,0.2)]";
                            $labelCls = "$labelBase text-secondary";
                        } else {
                            $dotCls = "$dotBase bg-border text-text-muted";
                            $labelCls = "$labelBase text-text-muted";
                        }
                    @endphp
                    <div class="flex flex-col items-center relative z-10 flex-1">
                        <div class="{{ $dotCls }}" id="dot-{{ $status }}">
                            <span class="material-symbols-outlined text-xl">{{ $icons[$idx] }}</span>
                        </div>
                        <div class="{{ $labelCls }}" id="label-{{ $status }}">{{ $labels[$idx] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm mb-4">
        <div class="p-4">
            <div class="flex justify-between mb-2">
                <span class="text-xs text-text-muted">Meja</span>
                <span class="font-semibold">{{ $pesanan->no_meja }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="text-xs text-text-muted">Waktu Pesan</span>
                <span class="font-medium text-sm">{{ $pesanan->waktu_pesan->format('H:i, d M Y') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-xs text-text-muted">Total</span>
                <span class="font-bold text-secondary">{{ $pesanan->formatted_total }}</span>
            </div>
        </div>
    </div>

    <div id="rating-section" class="{{ $pesanan->status_pesanan === 'selesai' ? '' : 'hidden' }}">
        <a href="{{ route('rating.show', $pesanan->id_pesanan) }}" class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-br from-secondary to-secondary-dark text-white rounded-xl font-semibold no-underline">
            <span class="material-symbols-outlined text-xl" style="font-variation-settings: 'FILL' 1;">star</span> Beri Rating & Ulasan
        </a>
    </div>

    <div class="text-center mt-5">
        <p class="text-[0.7rem] text-text-muted flex items-center justify-center gap-1" id="polling-status">
            <span class="material-symbols-outlined text-sm animate-spin">sync</span> Auto-refresh setiap 10 detik
        </p>
    </div>
</div>
@endsection

@section('scripts')
<script>
const statusMap = ['baru', 'diproses', 'selesai'];
const iconMap = ['description', 'local_fire_department', 'done_all'];

function pollStatus() {
    fetch('{{ route("order.status.json", $pesanan->id_pesanan) }}')
        .then(r => r.json())
        .then(data => {
            const payBadge = document.getElementById('payment-badge');
            const payColors = { lunas: 'bg-green-100 text-green-800', menunggu: 'bg-yellow-100 text-yellow-800', gagal: 'bg-red-100 text-red-800' };
            const payIcons = { lunas: 'check_circle', menunggu: 'schedule', gagal: 'cancel' };
            payBadge.className = 'inline-flex items-center gap-1 px-4 py-1.5 rounded-full text-sm font-semibold ' + (payColors[data.status_pembayaran] || 'bg-red-100 text-red-800');
            payBadge.innerHTML = '<span class="material-symbols-outlined text-lg">' + (payIcons[data.status_pembayaran] || 'help') + '</span> ' + data.status_pembayaran.charAt(0).toUpperCase() + data.status_pembayaran.slice(1);

            const currentIdx = statusMap.indexOf(data.status_pesanan);
            statusMap.forEach((status, idx) => {
                const dot = document.getElementById('dot-' + status);
                const label = document.getElementById('label-' + status);
                dot.className = 'w-10 h-10 rounded-full flex items-center justify-center mb-2 transition-all z-10';
                label.className = 'text-[0.7rem] font-semibold text-center';
                dot.innerHTML = '<span class="material-symbols-outlined text-xl">' + iconMap[idx] + '</span>';
                if (idx < currentIdx) {
                    dot.classList.add('bg-success', 'text-white', 'shadow-[0_0_0_6px_rgba(39,174,96,0.15)]');
                    label.classList.add('text-success');
                } else if (idx === currentIdx) {
                    dot.classList.add('bg-secondary', 'text-white', 'animate-pulse-dot', 'shadow-[0_0_0_6px_rgba(212,36,38,0.2)]');
                    label.classList.add('text-secondary');
                } else {
                    dot.classList.add('bg-border', 'text-text-muted');
                    label.classList.add('text-text-muted');
                }
            });
            if (data.status_pesanan === 'selesai') document.getElementById('rating-section').classList.remove('hidden');
        }).catch(() => {});
}
setInterval(pollStatus, 10000);
</script>
@endsection
