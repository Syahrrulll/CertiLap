<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kerusakan;
use App\Models\Gejala;
use App\Models\BasisPengetahuan;
use App\Models\RiwayatDiagnosa;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'kerusakan' => Kerusakan::count(),
            'gejala' => Gejala::count(),
            'rules' => BasisPengetahuan::count(),
            'diagnosa' => RiwayatDiagnosa::count(),
            'users' => User::where('role', 'user')->count(),
        ];

        $riwayatTerbaru = RiwayatDiagnosa::with(['user', 'kerusakanUtama'])
            ->latest('tanggal_diagnosa')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'riwayatTerbaru'));
    }
}

