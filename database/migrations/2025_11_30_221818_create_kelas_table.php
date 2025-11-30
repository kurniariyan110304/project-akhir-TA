<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();  // id INT

            $table->integer('semester');  // semester INT

            $table->string('kode', 50);   // kode VARCHAR

            $table->unsignedBigInteger('matakuliah_id'); // relasi ke tabel matakuliah
            $table->unsignedBigInteger('dosen_id');      // relasi ke tabel dosen

            $table->string('ruang', 100)->nullable();    // ruang VARCHAR
            $table->string('jam', 100)->nullable();      // jam VARCHAR

            $table->enum('hari', [
                'Senin',
                'Selasa',
                'Rabu',
                'Kamis',
                'Jumat',
                'Sabtu',
            ]);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};