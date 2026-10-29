<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surveys', function (Blueprint $table) {

        if (!Schema::hasColumn('surveys', 'q1')) {
            $table->boolean('q1')->nullable()->after('customer_photo');
        }

        if (!Schema::hasColumn('surveys', 'q2')) {
            $table->boolean('q2')->nullable();
        }

        if (!Schema::hasColumn('surveys', 'q3')) {
            $table->boolean('q3')->nullable();
        }

        if (!Schema::hasColumn('surveys', 'nps_score')) {
            $table->integer('nps_score')->nullable();
        }

        });
    }

    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {

            $table->dropColumn([
                'q1',
                'q2',
                'q3',
                'nps_score'
            ]);

        });
    }
};