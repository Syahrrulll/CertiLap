# Spesifikasi Teknis Algoritma Certainty Factor & Skenario Pengujian

Dokumen ini merinci logika komputasi mesin inferensi pada `core/CertaintyFactor.php` agar 100% sesuai dengan rumus matematis pada Bab II (2.3 & 2.9) dan pengujian pada Bab III (3.6) proposal penelitian.

---

## 1. Skala Parameter Keyakinan

### A. Bobot Pakar ($CF_{pakar}$)
Diperoleh dari teknisi ahli (pakar) melalui nilai *Measure of Belief* ($MB$) dan *Measure of Disbelief* ($MD$):
$$CF_{pakar}(H, E) = MB(H, E) - MD(H, E)$$

Rentang nilai berada di antara $-1.0$ (*Pasti Tidak*) hingga $+1.0$ (*Pasti*).

### B. Bobot Keyakinan Pengguna ($CF_{user}$)
Ditampilkan sebagai pilihan *dropdown* pada form diagnosa:
| Label Pilihan User | Nilai $CF_{user}$ |
| :--- | :---: |
| Tidak Dialami (diabaikan) | `0.0` |
| Tidak Yakin | `0.2` |
| Kurang Yakin | `0.4` |
| Cukup Yakin | `0.6` |
| Yakin | `0.8` |
| Sangat Yakin | `1.0` |

---

## 2. Alur Komputasi Mesin Inferensi

Ketika pengguna mengirimkan daftar gejala yang dipilih (hanya gejala dengan $CF_{user} > 0$):

1. **Pencocokan Aturan (Rule Matching):**
   Sistem mengambil seluruh data dari tabel `basis_pengetahuan` yang memiliki `id_gejala` sesuai dengan daftar gejala pilihan pengguna, lalu mengelompokkannya berdasarkan `id_kerusakan`.

2. **Perhitungan $CF$ Tiap Gejala (Sequential / Parallel Rule):**
   Untuk setiap gejala $i$ yang cocok pada suatu kerusakan $K$:
   $$CF_{gejala, i} = CF_{pakar, i} \times CF_{user, i}$$

3. **Penggabungan Nilai $CF$ ($CF_{combine}$):**
   Jika pada kerusakan $K$ terdapat lebih dari satu gejala yang cocok, lakukan penggabungan secara iteratif mulai dari gejala ke-1 dan ke-2, lalu hasilnya digabungkan dengan gejala ke-3, dan seterusnya menggunakan 3 kondisi matematis:
   * **Jika $CF_1 \ge 0$ dan $CF_2 \ge 0$ (Keduanya Positif):**
     $$CF_{combine} = CF_1 + CF_2 \times (1 - CF_1)$$
   * **Jika $CF_1 < 0$ dan $CF_2 < 0$ (Keduanya Negatif):**
     $$CF_{combine} = CF_1 + CF_2 \times (1 + CF_1)$$
   * **Jika salah satu Positif dan lainnya Negatif:**
     $$CF_{combine} = \frac{CF_1 + CF_2}{1 - \min(|CF_1|, |CF_2|)}$$

4. **Konversi Persentase & Pengurutan (Ranking):**
   $$\text{Persentase} = CF_{combine} \times 100\%$$
   Urutkan seluruh kandidat kerusakan dari nilai persentase **tertinggi ke terendah** (*descending*). Kerusakan di urutan pertama ditetapkan sebagai **Diagnosa Utama**.

---

## 3. Implementasi Referensi PHP (`core/CertaintyFactor.php`)

```php
<?php

class CertaintyFactor {

    /**
     * Menggabungkan dua nilai CF sesuai 3 skenario matematis
     */
    public static function combine(float $cf1, float $cf2): float {
        if ($cf1 >= 0 && $cf2 >= 0) {
            return $cf1 + $cf2 * (1 - $cf1);
        } elseif ($cf1 < 0 && $cf2 < 0) {
            return $cf1 + $cf2 * (1 + $cf1);
        } else {
            $denominator = 1 - min(abs($cf1), abs($cf2));
            if ($denominator == 0) {
                return 0.0;
            }
            return ($cf1 + $cf2) / $denominator;
        }
    }

    /**
     * Memproses diagnosa berdasarkan input gejala user
     * @param array $inputUser Format: [id_gejala => cf_user, ...]
     * @param array $rules     Data dari tabel basis_pengetahuan JOIN kerusakan
     * @return array           Hasil diagnosa terurut descending
     */
    public static function hitungDiagnosa(array $inputUser, array $rules): array {
        $grouped = [];

        // 1. Kelompokkan aturan yang cocok berdasarkan id_kerusakan
        foreach ($rules as $rule) {
            $idGejala = $rule['id_gejala'];
            if (isset($inputUser[$idGejala]) && $inputUser[$idGejala] > 0) {
                $idKerusakan = $rule['id_kerusakan'];
                $cfUser      = (float) $inputUser[$idGejala];
                $cfPakar     = (float) $rule['cf_pakar'];
                $cfGejala    = $cfPakar * $cfUser;

                if (!isset($grouped[$idKerusakan])) {
                    $grouped[$idKerusakan] = [
                        'id_kerusakan'   => $idKerusakan,
                        'kode_kerusakan' => $rule['kode_kerusakan'],
                        'nama_kerusakan' => $rule['nama_kerusakan'],
                        'solusi'         => $rule['solusi'],
                        'detail_gejala'  => [],
                        'cf_akhir'       => null
                    ];
                }

                $grouped[$idKerusakan]['detail_gejala'][] = [
                    'id_gejala' => $idGejala,
                    'cf_pakar'  => $cfPakar,
                    'cf_user'   => $cfUser,
                    'cf_gejala' => round($cfGejala, 4)
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
            $cfAkhir = max(0, $item['cf_akhir']); // Batasi minimal 0% untuk tampilan
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
}
```

---

## 4. Panduan Pengujian Sistem (Bab 3.6)

### A. Checklist Pengujian Fungsional (*Black-Box*)
- [ ] Login Admin berhasil mengarahkan ke Dashboard Admin; login salah menampilkan pesan error.
- [ ] Admin dapat menambah, mengedit, dan menghapus data Kerusakan, Gejala, dan Basis Pengetahuan (CF Pakar).
- [ ] User dapat mendaftar akun baru dan login ke halaman User.
- [ ] Form Diagnosa menolak submit jika tidak ada satupun gejala yang dipilih.
- [ ] Mesin inferensi menghitung $CF_{combine}$ dan menampilkan kerusakan tertinggi beserta solusinya.
- [ ] Hasil diagnosa tersimpan otomatis dan muncul di halaman Riwayat Diagnosa User & Admin.

### B. Rumus Evaluasi Validitas / Akurasi
Pengujian dilakukan pada minimal 15 kasus kerusakan nyata dengan membandingkan keluaran sistem terhadap diagnosa manual teknisi:
$$\text{Nilai Akurasi} = \frac{\text{Jumlah Data Diagnosa Sesuai}}{\text{Total Jumlah Data Uji}} \times 100\%$$