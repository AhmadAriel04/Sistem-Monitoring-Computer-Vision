<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cv_sessions', function (Blueprint $table) {

            $table->id();

            /*
             * ID unik yang dibuat oleh Computer Vision.
             * Contoh:
             * 3f2a91c4-8e12-4d5c-9a21-123456789abc
             */
            $table->string('session_uid', 64)->unique();

            /*
             * Pegawai yang dilayani pada sesi tersebut.
             */
            $table->foreignId('employee_id')
                ->constrained('employees')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            /*
             * Identitas loket/kamera.
             * Contoh: CS-01
             */
            $table->string('counter_code', 32);

            /*
             * Waktu sesi dimulai dan selesai.
             */
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();

            /*
             * Lama nasabah berada dalam sesi.
             */
            $table->decimal('duration_seconds', 8, 2)->nullable();

            /*
             * Hasil akhir CV.
             */
            $table->unsignedTinyInteger('rating')->nullable();

            /*
             * Nilai kontinu sebelum dibulatkan.
             * Contoh: 4.12
             */
            $table->decimal('score', 4, 2)->nullable();

            /*
             * Confidence keseluruhan.
             * Range 0.0000 - 1.0000
             */
            $table->decimal('confidence', 5, 4)->nullable();

            /*
             * Emosi dominan.
             */
            $table->string('dominant_emotion', 20)->nullable();

            /*
             * Distribusi:
             *
             * {
             *   "happy": 0.58,
             *   "neutral": 0.36,
             *   "sad": 0.04,
             *   "angry": 0.02
             * }
             */
            $table->json('distribution')->nullable();

            /*
             * Valence akhir.
             */
            $table->decimal('valence', 5, 4)->nullable();

            /*
             * Statistik proses CV.
             */
            $table->unsignedInteger('frames_seen')->nullable();
            $table->unsignedInteger('samples_used')->nullable();
            $table->decimal('effective_samples', 7, 2)->nullable();

            /*
             * Detail enam komponen confidence:
             *
             * adequacy
             * agreement
             * stability
             * model
             * quality
             * decisiveness
             */
            $table->json('confidence_components')->nullable();

            /*
             * Versi model yang digunakan.
             * Contoh: best.pt-v1
             */
            $table->string('model_version', 64)->nullable();

            /*
             * Status proses sesi.
             *
             * completed
             * pending_confirmation
             * confirmed
             * rejected
             */
            $table->string('status', 32)->default('completed');

            /*
             * Apakah sesi membutuhkan konfirmasi
             * dari nasabah melalui kiosk.
             */
            $table->boolean('needs_confirmation')->default(false);

            /*
             * Jawaban konfirmasi:
             *
             * true  = Ya
             * false = Tidak
             * null  = belum menjawab
             */
            $table->boolean('confirmation_answer')->nullable();

            $table->timestamp('confirmed_at')->nullable();

            /*
             * Batas waktu konfirmasi di kiosk.
             */
            $table->timestamp('expires_at')->nullable();

            /*
             * Sumber data.
             *
             * cv_auto
             * cv_confirmed
             */
            $table->string('source', 32)->default('cv_auto');

            $table->timestamps();

            /*
             * Index untuk endpoint pending.
             */
            $table->index(
                ['counter_code', 'needs_confirmation', 'confirmed_at'],
                'cv_sessions_pending_index'
            );

            /*
             * Index dashboard / analytics.
             */
            $table->index(
                ['employee_id', 'created_at'],
                'cv_sessions_employee_created_index'
            );

            $table->index(
                ['source', 'created_at'],
                'cv_sessions_source_created_index'
            );

            $table->index(
                ['status', 'created_at'],
                'cv_sessions_status_created_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cv_sessions');
    }
};