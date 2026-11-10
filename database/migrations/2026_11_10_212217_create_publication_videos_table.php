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
        Schema::create('publication_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('desc');
            $table->string('url', 2048);
            $table->enum('is_active', ['0', '1'])->default('0');
            $table->timestamps();

            $table->index(['is_active', 'created_at']);
        });

        if (Schema::hasTable('videos')) {
            DB::table('publication_videos')->insertUsing(
                ['title', 'desc', 'url', 'is_active', 'created_at', 'updated_at'],
                DB::table('videos')->select('title', 'desc', 'url', 'is_active', 'created_at', 'updated_at')
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publication_videos');
    }
};
