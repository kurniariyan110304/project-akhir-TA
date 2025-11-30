<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prodis', function (Blueprint $table) {
            $table->id(); // id INT AUTO_INCREMENT
            $table->string('kode', 50)->unique();   // kode VARCHAR
            $table->string('nama', 255);            // nama VARCHAR
            $table->string('kaprodi', 255)->nullable(); // kaprodi VARCHAR (boleh kosong)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prodis');
    }
};