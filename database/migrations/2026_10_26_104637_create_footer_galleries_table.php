<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Galeri foto pada footer yang bisa diganti dari panel admin.
     */
    public function up(): void
    {
        if (Schema::hasTable('footer_galleries')) {
            return;
        }

        Schema::create('footer_galleries', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('caption')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->enum('is_active', ['0', '1'])->default('0');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('footer_galleries');
    }
};
