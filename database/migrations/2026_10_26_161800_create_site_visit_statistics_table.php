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
        Schema::create('site_visit_statistics', function (Blueprint $table) {
            $table->id();
            $table->date('visit_date');
            $table->string('browser', 30);
            $table->unsignedBigInteger('visits')->default(0);
            $table->unique(['visit_date', 'browser']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_visit_statistics');
    }
};
