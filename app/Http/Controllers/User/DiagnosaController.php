<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gejala;
use App\Models\BasisPengetahuan;
use App\Models\RiwayatDiagnosa;
use App\Models\DetailRiwayat;
use App\Services\CertaintyFactorService;

class DiagnosaController extends Controller
{
    public function index()
    {
        $allGejala = Gejala::orderBy('kode_gejala')->get();
        $keyakinanOptions = [
            '0'   => 'Tidak Dialami',
            '0.2' => 'Tidak Yakin (0.2)',
            '0.4' => 'Kurang Yakin (0.4)',
            '0.6' => 'Cukup Yakin (0.6)',
            '0.8' => 'Yakin (0.8)',
            '1.0' => 'Sangat Yakin (1.0)',
        ];

        return view('user.diagnosa.index', compact('allGejala', 'keyakinanOptions'));
    }

    public function proses(Request $request)
    {
        $gejalaInput = $request->input('gejala', []);
        $inputUser = [];

        $labelMap = [
            '0.2' => 'Tidak Yakin',
            '0.4' => 'Kurang Yakin',
            '0.6' => 'Cukup Yakin',
            '0.8' => 'Yakin',
            '1.0' => 'Sangat Yakin',
        ];

        foreach ($gejalaInput as $idG => $cf) {
            $cf = (float) $cf;
            if ($cf > 0) {
                $inputUser[(int)$idG] = $cf;
            }
        }

        if (empty($inputUser)) {
            return back()->with('error', 'Silakan pilih minimal 1 gejala yang dialami beserta tingkat keyakinannya.');
        }

        // Ambil rules dengan eager loading
        $rules = BasisPengetahuan::with(['kerusakan', 'gejala'])->get();

        // Hitung dengan Certainty Factor Service
        $hasil = CertaintyFactorService::hitungDiagnosa($inputUser, $rules);

        $utama = !empty($hasil) ? $hasil[0] : null;
        $cfAkhir = $utama ? $utama['cf_akhir'] : 0;
        $persentase = $utama ? $utama['persentase'] : 0;
        $idKerusakanUtama = $utama ? $utama['id_kerusakan'] : null;

        $hasilLain = array_slice($hasil, 1);
        $hasilLainJson = !empty($hasilLain) ? json_encode(array_map(fn($h) => [
            'nama_kerusakan' => $h['nama_kerusakan'],
            'kode_kerusakan' => $h['kode_kerusakan'],
            'cf_akhir'       => $h['cf_akhir'],
            'persentase'     => $h['persentase'],
        ], $hasilLain)) : null;

        // Simpan ke riwayat_diagnosa
        $riwayat = RiwayatDiagnosa::create([
            'user_id' => auth()->id(),
            'id_kerusakan_utama' => $idKerusakanUtama,
            'nilai_cf_akhir' => $cfAkhir,
            'persentase' => $persentase,
            'hasil_lain_json' => $hasilLainJson,
            'tanggal_diagnosa' => now(),
        ]);

        // Simpan ke detail_riwayat
        foreach ($inputUser as $idG => $cf) {
            $label = $labelMap[(string)$cf] ?? 'Tidak Diketahui';
            DetailRiwayat::create([
                'id_riwayat' => $riwayat->id_riwayat,
                'id_gejala' => $idG,
                'cf_user' => $cf,
                'label_keyakinan' => $label,
            ]);
        }

        return redirect()->route('user.diagnosa.hasil', $riwayat->id_riwayat);
    }

    public function hasil($id)
    {
        $riwayat = RiwayatDiagnosa::with(['kerusakanUtama', 'details.gejala'])
            ->findOrFail($id);

        // Otorisasi: hanya pemilik atau admin yang bisa melihat
        if (!auth()->user()->isAdmin() && $riwayat->user_id !== auth()->id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        // Ambil CF Pakar untuk detail gejala pada kerusakan utama
        $detailsWithCf = [];
        foreach ($riwayat->details as $d) {
            $rule = BasisPengetahuan::where('id_kerusakan', $riwayat->id_kerusakan_utama)
                ->where('id_gejala', $d->id_gejala)
                ->first();

            $cfPakar = $rule ? (float)$rule->cf_pakar : null;
            $cfGejala = $cfPakar !== null ? round($cfPakar * (float)$d->cf_user, 4) : null;

            $detailsWithCf[] = [
                'kode_gejala' => $d->gejala->kode_gejala ?? '',
                'nama_gejala' => $d->gejala->nama_gejala ?? '',
                'label_keyakinan' => $d->label_keyakinan,
                'cf_user' => $d->cf_user,
                'cf_pakar' => $cfPakar,
                'cf_gejala' => $cfGejala,
            ];
        }

        $hasilLain = $riwayat->hasil_lain_json ? json_decode($riwayat->hasil_lain_json, true) : [];
        $kategori = CertaintyFactorService::getKategori($riwayat->persentase);
        $kategoriClass = CertaintyFactorService::getKategoriClass($riwayat->persentase);

        return view('user.diagnosa.hasil', compact('riwayat', 'detailsWithCf', 'hasilLain', 'kategori', 'kategoriClass'));
    }
}

