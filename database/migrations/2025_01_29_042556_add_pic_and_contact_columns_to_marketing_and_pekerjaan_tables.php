<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('marketing', function (Blueprint $table) {
            $table->string('nama_pic')->nullable();
            $table->string('no_telp')->nullable();
        });

        Schema::table('pekerjaan', function (Blueprint $table) {
            $table->string('nama_pic')->nullable();
            $table->string('no_telp')->nullable();
        });
    }

    public function down()
    {
        Schema::table('marketing', function (Blueprint $table) {
            $table->dropColumn(['nama_pic', 'no_telp']);
        });

        Schema::table('pekerjaan', function (Blueprint $table) {
            $table->dropColumn(['nama_pic', 'no_telp']);
        });
    }
};