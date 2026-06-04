<?php

namespace App\Services;

use App\Models\Menu;

class SAWService
{
    const BOBOT_HARGA       = 0.30;
    const BOBOT_POPULARITAS = 0.25;
    const BOBOT_RATING      = 0.45;

    public function calculate(): array
    {
        return $this->calculateDetailed()['ranking'];
    }

    /**
     * Full SAW calculation with detailed breakdown.
     * Returns: bobot, raw data, min/max, normalized, ranking
     */
    public function calculateDetailed(): array
    {
        $bundles = Menu::where('is_bundle', true)->where('is_active', true)->get();

        $bobot = [
            'c1' => ['nama' => 'Harga (C1)', 'nilai' => self::BOBOT_HARGA, 'tipe' => 'Cost'],
            'c2' => ['nama' => 'Popularitas (C2)', 'nilai' => self::BOBOT_POPULARITAS, 'tipe' => 'Benefit'],
            'c3' => ['nama' => 'Rating (C3)', 'nilai' => self::BOBOT_RATING, 'tipe' => 'Benefit'],
        ];

        if ($bundles->isEmpty()) {
            return [
                'bobot' => $bobot,
                'raw' => [],
                'min_max' => [],
                'normalized' => [],
                'ranking' => [],
            ];
        }

        // Raw criteria values
        $data = $bundles->map(function ($item) {
            return [
                'id'      => $item->id_menu,
                'nama'    => $item->nama_menu,
                'harga'   => $item->harga_c1,
                'gambar'  => $item->gambar,
                'c1'      => $item->harga_c1,
                'c2'      => $item->total_order_c2,
                'c3'      => $item->jumlah_rating > 0 ? $item->rating_rata_rata_c3 : 3.0,
                'menu'    => $item,
            ];
        })->toArray();

        // If all C2 = 0, equalize
        $allZeroC2 = collect($data)->every(fn($d) => $d['c2'] == 0);
        if ($allZeroC2) {
            $data = array_map(fn($d) => array_merge($d, ['c2' => 1]), $data);
        }

        // Min/Max
        $minC1 = min(array_column($data, 'c1'));
        $maxC2 = max(array_column($data, 'c2'));
        $maxC3 = max(array_column($data, 'c3'));

        $minMax = [
            'c1' => ['label' => 'Min C1', 'value' => $minC1],
            'c2' => ['label' => 'Max C2', 'value' => $maxC2],
            'c3' => ['label' => 'Max C3', 'value' => $maxC3],
        ];

        // Normalization & Preference
        foreach ($data as &$item) {
            $r1 = ($minC1 / $item['c1']);
            $r2 = ($maxC2 > 0) ? ($item['c2'] / $maxC2) : 1;
            $r3 = ($maxC3 > 0) ? ($item['c3'] / $maxC3) : 1;

            $item['r1'] = round($r1, 4);
            $item['r2'] = round($r2, 4);
            $item['r3'] = round($r3, 4);
            $item['vi'] = round(
                (self::BOBOT_HARGA * $r1) +
                (self::BOBOT_POPULARITAS * $r2) +
                (self::BOBOT_RATING * $r3),
                4
            );
        }

        // Sort by Vi descending
        usort($data, fn($a, $b) => $b['vi'] <=> $a['vi']);

        // Add rank
        foreach ($data as $idx => &$item) {
            $item['rank'] = $idx + 1;
        }

        return [
            'bobot'      => $bobot,
            'raw'        => $data,
            'min_max'    => $minMax,
            'normalized' => $data, // same array contains r1,r2,r3
            'ranking'    => $data,
        ];
    }
}
