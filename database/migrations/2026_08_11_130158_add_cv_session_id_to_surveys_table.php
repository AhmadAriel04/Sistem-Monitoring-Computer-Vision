<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surveys', function (Blueprint $table) {

            if (!Schema::hasColumn('surveys', 'cv_session_id')) {

                $table->foreignId('cv_session_id')
                    ->nullable()
                    ->after('employee_id')
                    ->constrained('cv_sessions')
                    ->nullOnDelete()
                    ->cascadeOnUpdate();
            }

            $table->index(
                ['cv_session_id', 'source'],
                'surveys_cv_session_source_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {

            if (Schema::hasColumn('surveys', 'cv_session_id')) {

                $table->dropForeign([
                    'cv_session_id'
                ]);

                $table->dropIndex(
                    'surveys_cv_session_source_index'
                );

                $table->dropColumn('cv_session_id');
            }
        });
    }
};