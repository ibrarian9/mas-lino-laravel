@extends('layouts.admin')

@section('page-title', 'QR Code Generator')

@section('content')
<div class="bg-white rounded-xl shadow-sm max-w-[500px] mx-auto">
    <div class="px-5 py-3 border-b border-cream font-semibold text-sm text-primary flex items-center gap-1">
        <span class="material-symbols-outlined text-lg">qr_code</span> QR Code — Es Coklat Mas Lino
    </div>
    <div class="p-6 text-center">
        <p class="text-sm text-text-muted mb-5">
            Cetak QR Code ini dan letakkan di setiap meja. Pelanggan cukup scan untuk mulai memesan.
        </p>
        <div class="bg-white p-6 rounded-2xl border-2 border-dashed border-border inline-block mb-4">
            {!! $qrCode !!}
        </div>
        <div class="mt-2">
            <p class="text-xs text-text-muted">URL Tujuan:</p>
            <code class="text-sm bg-light-bg px-3 py-1.5 rounded-md">{{ $url }}</code>
        </div>
        <div class="mt-5">
            <button class="px-6 py-2.5 bg-secondary text-white rounded-xl font-semibold text-sm border-none cursor-pointer hover:opacity-90 inline-flex items-center gap-1" onclick="window.print()">
                <span class="material-symbols-outlined text-lg">print</span> Cetak QR Code
            </button>
        </div>
    </div>
</div>
@endsection
