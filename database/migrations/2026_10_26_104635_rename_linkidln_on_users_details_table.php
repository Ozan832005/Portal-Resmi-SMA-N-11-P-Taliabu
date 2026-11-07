<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Migrasi awal membuat kolom `linkidln`, sedangkan seluruh controller &
     * form memakai `linkedln` sehingga insert guru gagal
     * (SQLSTATE[42S22] Unknown column 'linkedln').
     */
    public function up(): void
    {
        if (Schema::hasColumn('users_details', 'linkidln') && ! Schema::hasColumn('users_details', 'linkedln')) {
            Schema::table('users_details', function (Blueprint $table) {
                $table->renameColumn('linkidln', 'linkedln');
            });
        }

        if (! Schema::hasColumn('users_details', 'linkedln')) {
            Schema::table('users_details', function (Blueprint $table) {
                $table->string('linkedln')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users_details', 'linkedln') && ! Schema::hasColumn('users_details', 'linkidln')) {
            Schema::table('users_details', function (Blueprint $table) {
                $table->renameColumn('linkedln', 'linkidln');
            });
        }
    }
};
