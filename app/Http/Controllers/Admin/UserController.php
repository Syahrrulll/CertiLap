<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $dataList = User::withCount('riwayat')->latest()->get();
        return view('admin.users.index', compact('dataList'));
    }

    public function destroy($id)
    {
        if (auth()->id() == $id) {
            return back()->with('error', 'Tidak dapat menghapus akun Anda sendiri.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}

