<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BasisPengetahuan;
use App\Models\Kerusakan;
use App\Models\Gejala;

class BasisPengetahuanController extends Controller
{
    public function index()
    {
        $dataList = BasisPengetahuan::with(['kerusakan', 'gejala'])->get();
        $allKerusakan = Kerusakan::orderBy('kode_kerusakan')->get();
        $allGejala = Gejala::orderBy('kode_gejala')->get();

        return view('admin.rules.index', compact('dataList', 'allKerusakan', 'allGejala'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kerusakan' => ['required', 'exists:kerusakan,id_kerusakan'],
            'id_gejala' => ['required', 'exists:gejala,id_gejala'],
            'mb' => ['required', 'numeric', 'min:0', 'max:1'],
            'md' => ['required', 'numeric', 'min:0', 'max:1'],
        ]);

        $cf = round($validated['mb'] - $validated['md'], 2);

        // Periksa apakah relasi sudah ada
        $exists = BasisPengetahuan::where('id_kerusakan', $validated['id_kerusakan'])
            ->where('id_gejala', $validated['id_gejala'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Kombinasi kerusakan dan gejala ini sudah ada.');
        }

        BasisPengetahuan::create([
            'id_kerusakan' => $validated['id_kerusakan'],
            'id_gejala' => $validated['id_gejala'],
            'mb' => $validated['mb'],
            'md' => $validated['md'],
            'cf_pakar' => $cf,
        ]);

        return redirect()->route('admin.rules.index')->with('success', 'Rule basis pengetahuan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $rule = BasisPengetahuan::findOrFail($id);

        $validated = $request->validate([
            'id_kerusakan' => ['required', 'exists:kerusakan,id_kerusakan'],
            'id_gejala' => ['required', 'exists:gejala,id_gejala'],
            'mb' => ['required', 'numeric', 'min:0', 'max:1'],
            'md' => ['required', 'numeric', 'min:0', 'max:1'],
        ]);

        $cf = round($validated['mb'] - $validated['md'], 2);

        // Periksa duplikasi dengan rule lain
        $duplicate = BasisPengetahuan::where('id_kerusakan', $validated['id_kerusakan'])
            ->where('id_gejala', $validated['id_gejala'])
            ->where('id_rule', '!=', $id)
            ->exists();

        if ($duplicate) {
            return back()->with('error', 'Kombinasi kerusakan dan gejala tersebut sudah digunakan pada rule lain.');
        }

        $rule->update([
            'id_kerusakan' => $validated['id_kerusakan'],
            'id_gejala' => $validated['id_gejala'],
            'mb' => $validated['mb'],
            'md' => $validated['md'],
            'cf_pakar' => $cf,
        ]);

        return redirect()->route('admin.rules.index')->with('success', 'Rule basis pengetahuan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $rule = BasisPengetahuan::findOrFail($id);
        $rule->delete();

        return redirect()->route('admin.rules.index')->with('success', 'Rule basis pengetahuan berhasil dihapus.');
    }
}

