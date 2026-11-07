<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fasilitas sekolah yang dikelola dari panel admin dan ditampilkan
     * pada bagian statistik di halaman depan.
     */
    public function up(): void
    {
        if (Schema::hasTable('fasilitas')) {
            return;
        }

        Schema::create('fasilitas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('desc')->nullable();
            $table->string('image')->nullable();
            $table->enum('is_active', ['0', '1'])->default('0');
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fasilitas');
    }
};
