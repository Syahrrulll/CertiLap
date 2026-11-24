<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KerusakanController;
use App\Http\Controllers\Admin\GejalaController;
use App\Http\Controllers\Admin\BasisPengetahuanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RiwayatController as AdminRiwayatController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\DiagnosaController;
use App\Http\Controllers\User\RiwayatController as UserRiwayatController;
use App\Http\Controllers\User\ProfileController as UserProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page Publik
Route::get('/', function () {
    return view('landing');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes (Role: admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Kerusakan
    Route::get('/kerusakan', [KerusakanController::class, 'index'])->name('kerusakan.index');
    Route::post('/kerusakan', [KerusakanController::class, 'store'])->name('kerusakan.store');
    Route::put('/kerusakan/{id}', [KerusakanController::class, 'update'])->name('kerusakan.update');
    Route::delete('/kerusakan/{id}', [KerusakanController::class, 'destroy'])->name('kerusakan.destroy');

    // Gejala
    Route::get('/gejala', [GejalaController::class, 'index'])->name('gejala.index');
    Route::post('/gejala', [GejalaController::class, 'store'])->name('gejala.store');
    Route::put('/gejala/{id}', [GejalaController::class, 'update'])->name('gejala.update');
    Route::delete('/gejala/{id}', [GejalaController::class, 'destroy'])->name('gejala.destroy');

    // Basis Pengetahuan
    Route::get('/rules', [BasisPengetahuanController::class, 'index'])->name('rules.index');
    Route::post('/rules', [BasisPengetahuanController::class, 'store'])->name('rules.store');
    Route::put('/rules/{id}', [BasisPengetahuanController::class, 'update'])->name('rules.update');
    Route::delete('/rules/{id}', [BasisPengetahuanController::class, 'destroy'])->name('rules.destroy');

    // Pengguna
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Riwayat
    Route::get('/riwayat', [AdminRiwayatController::class, 'index'])->name('riwayat.index');

    // Profil Admin
    Route::get('/profil', [AdminProfileController::class, 'index'])->name('profil');
    Route::put('/profil/update', [AdminProfileController::class, 'updateProfile'])->name('profil.update');
    Route::put('/profil/password', [AdminProfileController::class, 'updatePassword'])->name('profil.password');
});

// User Routes (Role: user / all authenticated)
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Modul Diagnosa
    Route::get('/diagnosa', [DiagnosaController::class, 'index'])->name('diagnosa.index');
    Route::post('/diagnosa/proses', [DiagnosaController::class, 'proses'])->name('diagnosa.proses');
    Route::get('/diagnosa/hasil/{id}', [DiagnosaController::class, 'hasil'])->name('diagnosa.hasil');

    // Riwayat User
    Route::get('/riwayat', [UserRiwayatController::class, 'index'])->name('riwayat.index');

    // Profil User
    Route::get('/profil', [UserProfileController::class, 'index'])->name('profil');
    Route::put('/profil/update', [UserProfileController::class, 'updateProfile'])->name('profil.update');
    Route::put('/profil/password', [UserProfileController::class, 'updatePassword'])->name('profil.password');
});
