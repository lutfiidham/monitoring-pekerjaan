<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('marketing', function (Blueprint $table) {
            $table->string('nama_pic')->nullable()->after('alamat');
            $table->string('no_telp')->nullable()->after('nama_pic');
        });

        Schema::table('pekerjaan', function (Blueprint $table) {
            $table->string('nama_pic')->nullable()->after('pelanggan_id');
            $table->string('no_telp')->nullable()->after('nama_pic');
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