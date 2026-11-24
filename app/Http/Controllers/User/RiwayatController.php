<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\RiwayatDiagnosa;

class RiwayatController extends Controller
{
    public function index()
    {
        $dataList = RiwayatDiagnosa::with('kerusakanUtama')
            ->where('user_id', auth()->id())
            ->latest('tanggal_diagnosa')
            ->get();

        return view('user.riwayat.index', compact('dataList'));
    }
}

