<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kerusakan;

class KerusakanController extends Controller
{
    public function index()
    {
        $dataList = Kerusakan::orderBy('kode_kerusakan')->get();
        return view('admin.kerusakan.index', compact('dataList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_kerusakan' => ['required', 'string', 'max:10', 'unique:kerusakan,kode_kerusakan'],
            'nama_kerusakan' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'solusi' => ['required', 'string'],
        ]);

        Kerusakan::create($validated);

        return redirect()->route('admin.kerusakan.index')->with('success', 'Data kerusakan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $kerusakan = Kerusakan::findOrFail($id);

        $validated = $request->validate([
            'kode_kerusakan' => ['required', 'string', 'max:10', 'unique:kerusakan,kode_kerusakan,' . $id . ',id_kerusakan'],
            'nama_kerusakan' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'solusi' => ['required', 'string'],
        ]);

        $kerusakan->update($validated);

        return redirect()->route('admin.kerusakan.index')->with('success', 'Data kerusakan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kerusakan = Kerusakan::findOrFail($id);
        $kerusakan->delete();

        return redirect()->route('admin.kerusakan.index')->with('success', 'Data kerusakan berhasil dihapus.');
    }
}

