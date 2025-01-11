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
        Schema::create('pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_id')->constrained('marketing')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nomor_oc')->nullable();
            $table->string('nomor_order')->nullable();
            $table->string('nama_produk_atau_pekerjaan')->nullable();
            $table->integer('jumlah_produk')->nullable();
            $table->bigInteger('nilai_kontrak')->nullable();
            $table->string('status')->nullable();
            $table->text('progress')->nullable();
            $table->string('status_collecting_document')->nullable();
            $table->string('tahun')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pekerjaan');
    }
};
