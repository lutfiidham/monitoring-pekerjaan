<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabel arsip
        Schema::create('arsip', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pekerjaan_id')->constrained('pekerjaan')->cascadeOnDelete();
            $table->string('nama_arsip');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Tabel item_arsip
        Schema::create('item_arsip', function (Blueprint $table) {
            $table->id();
            $table->foreignId('arsip_id')->constrained('arsip')->cascadeOnDelete();
            $table->string('kategori'); // Sebagai folder virtual
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_arsip');
        Schema::dropIfExists('arsip');
    }
};
