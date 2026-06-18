@extends('layouts.customer')

@section('title', 'Rating — Es Coklat Mas Lino')

@section('content')
<div class="animate-[fadeIn_0.4s_ease]">
    <h2 class="text-lg font-bold text-primary mb-1 flex items-center gap-2">
        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span> Beri Rating
    </h2>
    <p class="text-xs text-text-muted mb-5">Pesanan {{ $pesanan->id_pesanan }}</p>

    <form action="{{ route('rating.store') }}" method="POST">
        @csrf
        <input type="hidden" name="id_pesanan" value="{{ $pesanan->id_pesanan }}">

        @foreach($pesanan->details as $idx => $detail)
            @php $rated = in_array($detail->id_menu, $existingRatings); @endphp
            <div class="bg-white rounded-2xl shadow-sm mb-3 {{ $rated ? 'opacity-60' : '' }}">
                <div class="p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center text-xl shrink-0 overflow-hidden">
                            @if($detail->menu->gambar)
                                <img src="{{ asset('storage/' . $detail->menu->gambar) }}" class="w-full h-full object-contain rounded-xl">
                            @else
                                🍫
                            @endif
                        </div>
                        <div>
                            <div class="text-sm font-semibold">{{ $detail->menu->nama_menu }}</div>
                            <div class="text-xs text-text-muted">x{{ $detail->kuantitas }}</div>
                        </div>
                    </div>

                    @if($rated)
                        <div class="flex items-center gap-2.5 p-3 rounded-xl text-sm bg-green-100 text-green-800">
                            <span class="material-symbols-outlined text-lg">check</span> Sudah diberi rating
                        </div>
                    @else
                        <input type="hidden" name="ratings[{{ $idx }}][id_menu]" value="{{ $detail->id_menu }}">
                        <div class="text-center mb-3">
                            <div class="star-rating">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="ratings[{{ $idx }}][nilai]" id="star-{{ $idx }}-{{ $i }}" value="{{ $i }}" {{ $i === 5 ? 'checked' : '' }}>
                                    <label for="star-{{ $idx }}-{{ $i }}"><span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span></label>
                                @endfor
                            </div>
                        </div>
                        <div>
                            <textarea name="ratings[{{ $idx }}][ulasan]" rows="2"
                                      class="w-full px-4 py-3 border-2 border-border rounded-xl font-sans text-sm resize-none transition-colors focus:outline-none focus:border-secondary focus:ring-3 focus:ring-secondary/15"
                                      placeholder="Tulis ulasan (opsional)..." maxlength="500"></textarea>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        @if(count($existingRatings) < $pesanan->details->count())
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-br from-secondary to-secondary-dark text-white rounded-xl font-semibold cursor-pointer border-none mt-2">
                <span class="material-symbols-outlined text-xl">send</span> Kirim Rating
            </button>
        @else
            <div class="text-center mt-4">
                <p class="text-sm text-success font-semibold flex items-center justify-center gap-1">
                    <span class="material-symbols-outlined text-lg">check_circle</span> Semua item sudah diberi rating. Terima kasih!
                </p>
                <a href="{{ route('order.status', $pesanan->id_pesanan) }}" class="inline-flex items-center gap-2 px-4 py-2.5 border-2 border-secondary text-secondary bg-transparent rounded-xl font-semibold no-underline mt-3">
                    <span class="material-symbols-outlined text-xl">arrow_back</span> Kembali
                </a>
            </div>
        @endif
    </form>
</div>
@endsection
