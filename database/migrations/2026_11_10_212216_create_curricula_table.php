<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('curricula', function (Blueprint $table) {
            $table->id();
            $table->string('label', 50);
            $table->string('title');
            $table->text('description');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });

        $timestamp = now();

        DB::table('curricula')->insert([
            [
                'label' => 'CP',
                'title' => 'Capaian Pembelajaran',
                'description' => 'Target pembelajaran yang dicapai siswa melalui pengalaman belajar yang terstruktur dan terukur.',
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'label' => 'ATP',
                'title' => 'Alur Tujuan Pembelajaran',
                'description' => 'Urutan pencapaian kompetensi agar proses belajar berjalan sistematis, progresif, dan terarah.',
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'label' => 'TP',
                'title' => 'Tujuan Pembelajaran',
                'description' => 'Tujuan spesifik setiap unit pembelajaran yang menjabarkan kompetensi siswa secara operasional.',
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'label' => 'Modul',
                'title' => 'Modul Ajar',
                'description' => 'Panduan pembelajaran guru yang memuat materi, aktivitas, tugas, asesmen, dan sumber belajar.',
                'is_active' => true,
                'sort_order' => 4,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curricula');
    }
};
