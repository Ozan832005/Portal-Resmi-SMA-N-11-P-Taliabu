<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ContentMigrationsTest extends TestCase
{
    public function test_content_migrations_preserve_curriculum_and_copy_existing_videos(): void
    {
        Schema::create('videos', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->text('desc');
            $table->string('url');
            $table->string('is_active')->default('0');
            $table->timestamps();
        });

        DB::table('videos')->insert([
            'title' => 'Video lama',
            'desc' => 'Video halaman situs yang sudah ada.',
            'url' => 'https://www.youtube.com/watch?v=abcDEF12345',
            'is_active' => '0',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $exitCode = Artisan::call('migrate', [
            '--database' => 'sqlite',
            '--path' => [
                'database/migrations/2026_11_10_212216_create_curricula_table.php',
                'database/migrations/2026_11_10_212217_create_publication_videos_table.php',
            ],
            '--force' => true,
            '--no-interaction' => true,
        ]);

        $this->assertSame(0, $exitCode);
        $this->assertDatabaseCount('curricula', 4);
        $this->assertDatabaseHas('curricula', [
            'label' => 'CP',
            'title' => 'Capaian Pembelajaran',
            'is_active' => 1,
        ]);
        $this->assertDatabaseCount('videos', 1);
        $this->assertDatabaseHas('publication_videos', [
            'title' => 'Video lama',
            'url' => 'https://www.youtube.com/watch?v=abcDEF12345',
            'is_active' => '0',
        ]);
    }
}
