<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();

            $table->integer('semester');
            $table->string('kode', 50)->nullable();

            $table->unsignedBigInteger('matakuliah_id');
            $table->unsignedBigInteger('dosen_id');

            $table->string('ruang', 100)->nullable();
            $table->string('jam', 100)->nullable();

            $table->enum('hari', [
                'Senin',
                'Selasa',
                'Rabu',
                'Kamis',
                'Jumat',
                'Sabtu',
            ])->nullable();

            $table->timestamps();

            $table->foreign('matakuliah_id')
                ->references('id')
                ->on('matakuliah')
                ->cascadeOnDelete();

            $table->foreign('dosen_id')
                ->references('id')
                ->on('dosen')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['matakuliah_id']);
            $table->dropForeign(['dosen_id']);
        });

        Schema::dropIfExists('kelas');
    }
};