<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('barbers', 'is_maintenance')) {
            Schema::table('barbers', function (Blueprint $table) {
                $table->boolean('is_maintenance')->default(false)->after('chair_code');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('barbers', 'is_maintenance')) {
            Schema::table('barbers', function (Blueprint $table) {
                $table->dropColumn('is_maintenance');
            });
        }
    }
};
