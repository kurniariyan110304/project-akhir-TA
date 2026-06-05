<?php

use App\Http\Controllers\AuthController;
use App\Models\Asdos;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\KelompokProject;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;
use App\Models\ProjectMahasiswa;
use App\Models\Tugas;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $stats = [
        'dosen' => Dosen::count(),
        'mahasiswa' => Mahasiswa::count(),
        'kelas' => Kelas::count(),
        'matakuliah' => Matakuliah::count(),
        'tugas' => Tugas::count(),
        'project_mahasiswa' => ProjectMahasiswa::count(),

        // Hitung jumlah kelompok unik, bukan jumlah anggota
        'kelompok_project' => KelompokProject::query()
            ->distinct('project_mahasiswa_id')
            ->count('project_mahasiswa_id'),

        'asdos' => Asdos::count(),
    ];

    return view('landing', compact('stats'));
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