<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatDiagnosa;

class RiwayatController extends Controller
{
    public function index()
    {
        $dataList = RiwayatDiagnosa::with(['user', 'kerusakanUtama'])
            ->latest('tanggal_diagnosa')
            ->get();

        return view('admin.riwayat.index', compact('dataList'));
    }
}

