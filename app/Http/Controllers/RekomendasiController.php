<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorData;

class RekomendasiController extends Controller
{
    public function index(Request $request)
    {
        $sensor = SensorData::latest()->first();

        if (!$sensor) {
            return view('rekomendasi', ['hasil' => null]);
        }

        // Ambil data dari sensor (dalam bentuk persen)
        $ch4_percent = $sensor->gas_metana; // Sudah persen
        $suhu = $sensor->suhu;
        $kelembapan = $sensor->kelembapan_tanah;

        // Ambil luas lahan dari input user (meter persegi)
        $luasMeter = $request->input('luas_lahan', 100); // default 100 m²

        // Hitung dosis pupuk dan rekomendasi
        $hasil = $this->hitungDosisPupuk($ch4_percent, $suhu, $kelembapan, $luasMeter);
        $rekomendasiPupuk = $this->getRekomendasiPupuk($ch4_percent);

        return view('rekomendasi', compact('hasil', 'sensor', 'luasMeter', 'rekomendasiPupuk'));
    }

    private function hitungDosisPupuk($ch4_percent, $suhu, $kelembapan, $luas_m2)
    {
        // Nilai referensi optimal
        $ch4_normal = 10.0; // 10% dianggap ambang aman
        $suhu_optimum = 30;
        $kelembapan_optimum = 60;

        // Bobot pengaruh
        $alpha = 1.0;
        $beta = 0.3;
        $delta = 0.4;
        $epsilon = 0.3;

        // Rasio CH4 maksimal 200% (untuk stabilitas)
        $ch4_ratio = min(2.0, $ch4_percent / $ch4_normal);

        // Hitung Kp
        $kp = $alpha
            - $beta * $ch4_ratio
            + $delta * (1 - abs(($suhu - $suhu_optimum) / 10))
            + $epsilon * (1 - abs(($kelembapan - $kelembapan_optimum) / 40));

        $kp = max(0.2, round($kp, 3));

        // Dosis standar: 5 kg per hektare (10.000 m²)
        $dosis_standar_kg_per_m2 = 5.0 / 10000;

        // Total dosis berdasarkan luas (kg)
        $dosis_per_m2 = $dosis_standar_kg_per_m2 * $kp;
        $total_dosis_kg = $dosis_per_m2 * $luas_m2;

        return [
            'Kp' => $kp,
            'dosis_per_m2_kg' => round($dosis_per_m2, 4),
            'total_pupuk_kg' => round($total_dosis_kg, 2)
        ];
    }

    private function getRekomendasiPupuk($ch4_percent)
{
    $rekomendasi = [];

    if ($ch4_percent > 10.0) { // ambang batas aman CH₄
        $rekomendasi[] = 'Kadar CH₄ melebihi batas aman. Terapkan kombinasi pemupukan dan teknik pengelolaan air sebagai berikut:';
        $rekomendasi[] = '<ul>
            <li>Gunakan pupuk kimia sesuai dosis:
                <ul>
                    <li><strong>Urea</strong>: 120 kg/ha</li>
                    <li><strong>SP-36</strong>: 45 kg/ha</li>
                    <li><strong>KCl</strong>: 60 kg/ha</li>
                </ul>
            </li>
            <li>Tambahkan <strong>pupuk kandang sapi matang</strong> sebanyak 2 ton/ha jika tersedia.</li>
            <li>Aplikasikan <strong>konsorsium bakteri</strong> seperti <em>Bacillus aryabhattai</em>, SI5, BD4, dan TH6 untuk menurunkan emisi CH₄.</li>
            <li>Gunakan teknik <strong>AWD (Alternate Wetting and Drying)</strong> untuk mengurangi akumulasi metana.</li>
        </ul>';
    } else {
        $rekomendasi[] = 'Kadar CH₄ dalam batas aman. Pertahankan kesuburan tanah dengan pemupukan berimbang:';
        $rekomendasi[] = '<ul>
            <li><strong>Urea</strong>: 120 kg/ha</li>
            <li><strong>SP-36</strong>: 45 kg/ha</li>
            <li><strong>KCl</strong>: 60 kg/ha</li>
            <li>Gunakan <strong>pupuk organik</strong> seperti kompos jerami atau pupuk kandang fermentasi.</li>
            <li>Disarankan penggunaan <strong>pupuk hayati</strong> seperti EM4 atau mikroba lokal.</li>
        </ul>';
    }

    return $rekomendasi;
}

}
