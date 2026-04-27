<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosen', function (Blueprint $table) {
            $table->id();
            $table->string('nidn', 20)->nullable();
            $table->string('nama', 50);
            $table->string('gelar_depan', 10)->nullable();
            $table->string('gelar_belakang', 20)->nullable();
            $table->char('jk', 1)->nullable();
            $table->string('tmp_lahir', 30)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};