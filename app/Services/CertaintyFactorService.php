<?php

namespace App\Services;

/**
 * Service Certainty Factor
 * Mengimplementasikan 3 skenario combine CF dan inferensi diagnosa
 * Berdasarkan Jurnal TIKomSiN Vol. 13 No. 1, 2025 (Widianto et al.)
 */
class CertaintyFactorService
{
    /**
     * Menggabungkan dua nilai CF sesuai 3 skenario matematis
     */
    public static function combine(float $cf1, float $cf2): float
    {
        if ($cf1 >= 0 && $cf2 >= 0) {
            // Skenario 1: Keduanya positif
            return $cf1 + $cf2 * (1 - $cf1);
        } elseif ($cf1 < 0 && $cf2 < 0) {
            // Skenario 2: Keduanya negatif
            return $cf1 + $cf2 * (1 + $cf1);
        } else {
            // Skenario 3: Salah satu positif, lainnya negatif
            $denominator = 1 - min(abs($cf1), abs($cf2));
            if ($denominator == 0) {
                return 0.0;
            }
            return ($cf1 + $cf2) / $denominator;
        }
    }

    /**
     * Memproses diagnosa berdasarkan input gejala user dan rules
     *
     * @param array $inputUser Format: [id_gejala => cf_user, ...]
     * @param iterable $rules  Data relasi BasisPengetahuan
     * @return array           Hasil diagnosa terurut descending
     */
    public static function hitungDiagnosa(array $inputUser, iterable $rules): array
    {
        $grouped = [];

        // 1. Kelompokkan aturan yang cocok berdasarkan id_kerusakan
        foreach ($rules as $rule) {
            $idGejala = (int) $rule->id_gejala;
            if (isset($inputUser[$idGejala]) && (float) $inputUser[$idGejala] > 0) {
                $idKerusakan = (int) $rule->id_kerusakan;
                $cfUser      = (float) $inputUser[$idGejala];
                $cfPakar     = (float) $rule->cf_pakar;
                $cfGejala    = $cfPakar * $cfUser;

                if (!isset($grouped[$idKerusakan])) {
                    $grouped[$idKerusakan] = [
                        'id_kerusakan'   => $idKerusakan,
                        'kode_kerusakan' => $rule->kerusakan->kode_kerusakan ?? '',
                        'nama_kerusakan' => $rule->kerusakan->nama_kerusakan ?? '',
                        'deskripsi'      => $rule->kerusakan->deskripsi ?? '',
                        'solusi'         => $rule->kerusakan->solusi ?? '',
                        'detail_gejala'  => [],
                        'cf_akhir'       => null,
                    ];
                }

                $grouped[$idKerusakan]['detail_gejala'][] = [
                    'id_gejala'   => $idGejala,
                    'kode_gejala' => $rule->gejala->kode_gejala ?? '',
                    'nama_gejala' => $rule->gejala->nama_gejala ?? '',
                    'cf_pakar'    => round($cfPakar, 4),
                    'cf_user'     => round($cfUser, 4),
                    'cf_gejala'   => round($cfGejala, 4),
                ];

                // 2. Hitung CF Combine secara iteratif
                if ($grouped[$idKerusakan]['cf_akhir'] === null) {
                    $grouped[$idKerusakan]['cf_akhir'] = $cfGejala;
                } else {
                    $grouped[$idKerusakan]['cf_akhir'] = self::combine(
                        $grouped[$idKerusakan]['cf_akhir'],
                        $cfGejala
                    );
                }
            }
        }

        // 3. Hitung persentase & format angka
        $hasil = [];
        foreach ($grouped as $item) {
            $cfAkhir = max(0, (float) $item['cf_akhir']);
            $item['cf_akhir']   = round($cfAkhir, 4);
            $item['persentase'] = round($cfAkhir * 100, 2);
            $hasil[] = $item;
        }

        // 4. Urutkan dari nilai persentase tertinggi ke terendah
        usort($hasil, function ($a, $b) {
            return $b['persentase'] <=> $a['persentase'];
        });

        return $hasil;
    }

    /**
     * Konversi persentase CF ke label kategori keyakinan
     */
    public static function getKategori(float $persentase): string
    {
        if ($persentase >= 75) return 'Keyakinan Tinggi';
        if ($persentase >= 40) return 'Kemungkinan Sedang';
        return 'Keyakinan Rendah';
    }

    /**
     * Konversi persentase CF ke class warna status
     */
    public static function getKategoriClass(float $persentase): string
    {
        if ($persentase >= 75) return 'success';
        if ($persentase >= 40) return 'warning';
        return 'danger';
    }
}

