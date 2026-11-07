<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom `desc` pada abouts & videos masih varchar(255) sehingga deskripsi
     * panjang gagal disimpan (SQLSTATE[22001] Data too long for column 'desc').
     */
    public function up(): void
    {
        Schema::table('abouts', function (Blueprint $table) {
            $table->text('desc')->change();
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->text('desc')->change();
        });
    }

    public function down(): void
    {
        Schema::table('abouts', function (Blueprint $table) {
            $table->string('desc')->change();
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->string('desc')->change();
        });
    }
};
