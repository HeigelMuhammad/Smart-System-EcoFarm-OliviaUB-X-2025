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
    
        $ch4 = $sensor->gas_metana;
        $co = $sensor->gas_karbon;
        $suhu = $sensor->suhu;
        $kelembapan = $sensor->kelembapan_tanah;
    
        // Ambil luas lahan dari input user
        $luasLahan = $request->input('luas_lahan', 1); // default 1 ha
    
        // Hitung dosis pupuk dan rekomendasi
        $hasil = $this->hitungDosisPupuk($ch4, $co, $suhu, $kelembapan, $luasLahan);
        $rekomendasiPupuk = $this->getRekomendasiPupuk($ch4);
    
        return view('rekomendasi', compact('hasil', 'sensor', 'luasLahan', 'rekomendasiPupuk'));
    }
    
    private function hitungDosisPupuk($ch4, $co, $suhu, $kelembapan, $luas)
    {
        $ch4_normal = 300;
        $co_normal = 5;
        $suhu_optimum = 30;
        $kelembapan_optimum = 60;
    
        $alpha = 1.0;
        $beta = 0.3;
        $gamma = 0.2;
        $delta = 0.2;
        $epsilon = 0.1;
    
        $kp = $alpha
            - $beta * ($ch4 / $ch4_normal)
            - $gamma * ($co / $co_normal)
            + $delta * (1 - abs(($suhu - $suhu_optimum) / 10))
            + $epsilon * (1 - abs(($kelembapan - $kelembapan_optimum) / 40));
    
        $dosis_standar = 5.0;
        $dosis_akhir = max(0, $dosis_standar * $kp);
        $total_dosis = $dosis_akhir * $luas;
    
        return [
            'Kp' => round($kp, 3),
            'dosis_pupuk' => round($dosis_akhir, 2),
            'total_pupuk' => round($total_dosis, 2)
        ];
    }
    
    private function getRekomendasiPupuk($ch4)
    {
        $rekomendasi = [];
    
        if ($ch4 > 300) {
            $rekomendasi[] = 'Gunakan <strong>Biofertilizer</strong> mengandung Bacillus aryabhattai, SI5, BD4, Azospirillum spp. (5–10 kg/ha granul atau 2–5 liter/ha cair).';
            $rekomendasi[] = 'Gunakan <strong>pupuk organik matang</strong> (hindari pupuk kandang segar).';
            $rekomendasi[] = 'Pertimbangkan <strong>urea berlapis (slow-release)</strong> seperti SCU atau NBPT.';
            $rekomendasi[] = 'Gunakan teknik <strong>AWD (Alternate Wetting & Drying)</strong> untuk mengurangi emisi CH₄.';
        } else {
            $rekomendasi[] = 'Kadar CH₄ masih tergolong aman, tetap gunakan pupuk organik dan hayati untuk menjaga keseimbangan mikroba tanah.';
        }
    
        return $rekomendasi;
    }
    
}
