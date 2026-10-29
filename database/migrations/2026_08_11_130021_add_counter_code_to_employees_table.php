<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'counter_code')) {
                $table->string('counter_code', 32)
                    ->nullable()
                    ->unique()
                    ->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (Schema::hasColumn('employees', 'counter_code')) {
                $table->dropUnique(['counter_code']);
                $table->dropColumn('counter_code');
            }
        });
    }
};