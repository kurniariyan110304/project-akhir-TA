<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('asdos', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('nim', 10); // Asdos biasanya mahasiswa

            // Ini BENAR, karena tabel 'users' bawaan Laravel menggunakan BIGINT
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            // --- PERBAIKAN DI SINI ---
            // Kita gunakan unsignedInteger agar tipenya INT, sesuai dengan tabel 'dosen' Anda
            $table->unsignedInteger('dosen_id');
            $table->foreign('dosen_id')->references('id')->on('dosen');

            // Lakukan hal yang sama untuk 'kelas_id'
            $table->unsignedInteger('kelas_id');
            $table->foreign('kelas_id')->references('id')->on('kelas');
            // --- AKHIR PERBAIKAN ---

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asdos');
    }
};
