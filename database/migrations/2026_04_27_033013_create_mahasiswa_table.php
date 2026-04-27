<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->string('nim', 10)->primary();
            $table->string('nama', 45);
            $table->char('jk', 1)->nullable();
            $table->string('tmp_lahir', 30)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('email', 40)->nullable();
            $table->integer('thn_masuk')->nullable();
            $table->unsignedBigInteger('prodi_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};