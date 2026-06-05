<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::delete('/admin/kelas-mahasiswa/{id}/hapus', function ($id) {
    DB::table('kelas_mahasiswa')
        ->where('id', $id)
        ->delete();

    return back();
})->name('admin.kelas-mahasiswa.destroy');

// Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
// Route::post('/login', [AuthController::class, 'login'])->name('login.post');
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout');