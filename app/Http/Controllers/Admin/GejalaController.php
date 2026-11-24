<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gejala;

class GejalaController extends Controller
{
    public function index()
    {
        $dataList = Gejala::orderBy('kode_gejala')->get();
        return view('admin.gejala.index', compact('dataList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_gejala' => ['required', 'string', 'max:10', 'unique:gejala,kode_gejala'],
            'nama_gejala' => ['required', 'string', 'max:255'],
        ]);

        Gejala::create($validated);

        return redirect()->route('admin.gejala.index')->with('success', 'Data gejala berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $gejala = Gejala::findOrFail($id);

        $validated = $request->validate([
            'kode_gejala' => ['required', 'string', 'max:10', 'unique:gejala,kode_gejala,' . $id . ',id_gejala'],
            'nama_gejala' => ['required', 'string', 'max:255'],
        ]);

        $gejala->update($validated);

        return redirect()->route('admin.gejala.index')->with('success', 'Data gejala berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $gejala = Gejala::findOrFail($id);
        $gejala->delete();

        return redirect()->route('admin.gejala.index')->with('success', 'Data gejala berhasil dihapus.');
    }
}

