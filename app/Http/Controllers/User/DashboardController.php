<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RiwayatDiagnosa;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalDiagnosa = RiwayatDiagnosa::where('user_id', $user->id)->count();
        $lastDiagnosa = RiwayatDiagnosa::with('kerusakanUtama')
            ->where('user_id', $user->id)
            ->latest('tanggal_diagnosa')
            ->first();

        return view('user.dashboard', compact('user', 'totalDiagnosa', 'lastDiagnosa'));
    }
}

