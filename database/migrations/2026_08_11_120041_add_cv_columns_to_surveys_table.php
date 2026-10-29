<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Kolom hasil computer vision pada tabel surveys.
 *
 * Catatan desain: hasil CV sengaja disimpan di tabel yang sama dengan
 * survey manual, tapi selalu dibedakan lewat kolom `source`. Dengan
 * begitu dashboard bisa menampilkan keduanya bersama-sama, sekaligus
 * bisa memisahkan mana rating yang berasal dari jawaban langsung
 * nasabah dan mana yang hasil pembacaan kamera. Tanpa pembeda ini,
 * dua jenis data dengan tingkat kepercayaan yang sangat berbeda akan
 * tercampur menjadi satu angka rata-rata yang tidak bisa ditafsirkan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surveys', function (Blueprint $table) {

            if (! Schema::hasColumn('surveys', 'session_uid')) {
                // UUID sesi dari sistem CV. Unique agar pengiriman ulang
                // dari outbox offline tidak menghasilkan baris ganda.
                $table->string('session_uid', 64)
                      ->nullable()
                      ->unique()
                      ->after('id');
            }

            if (! Schema::hasColumn('surveys', 'source')) {
                $table->enum('source', [
                    'manual',        // nasabah mengisi sendiri di kiosk
                    'cv_auto',       // murni hasil computer vision
                    'cv_confirmed',  // hasil CV yang sudah dikonfirmasi Ya/Tidak
                ])->default('manual')->after('rating');
            }

            if (! Schema::hasColumn('surveys', 'counter_code')) {
                $table->string('counter_code', 32)->nullable()->after('source');
            }

            if (! Schema::hasColumn('surveys', 'cv_score')) {
                // Skor kontinu sebelum dibulatkan (mis. 4.12).
                // Berguna untuk analisis; rata-rata dari nilai kontinu
                // jauh lebih halus daripada rata-rata bilangan bulat.
                $table->decimal('cv_score', 4, 2)->nullable();
            }

            if (! Schema::hasColumn('surveys', 'cv_confidence')) {
                $table->decimal('cv_confidence', 5, 4)->nullable();
            }

            if (! Schema::hasColumn('surveys', 'cv_emotion')) {
                $table->string('cv_emotion', 20)->nullable();
            }

            if (! Schema::hasColumn('surveys', 'cv_distribution')) {
                $table->json('cv_distribution')->nullable();
            }

            if (! Schema::hasColumn('surveys', 'cv_valence')) {
                $table->decimal('cv_valence', 5, 4)->nullable();
            }

            if (! Schema::hasColumn('surveys', 'cv_frames_seen')) {
                $table->unsignedInteger('cv_frames_seen')->nullable();
            }

            if (! Schema::hasColumn('surveys', 'cv_samples_used')) {
                $table->unsignedInteger('cv_samples_used')->nullable();
            }

            if (! Schema::hasColumn('surveys', 'cv_effective_samples')) {
                $table->decimal('cv_effective_samples', 7, 2)->nullable();
            }

            if (! Schema::hasColumn('surveys', 'cv_components')) {
                // Rincian komponen confidence. Disimpan supaya ketika
                // suatu sesi meleset, bisa ditelusuri komponen mana
                // yang jadi penyebabnya (blur? sampel kurang? goyang?).
                $table->json('cv_components')->nullable();
            }

            if (! Schema::hasColumn('surveys', 'cv_model_version')) {
                $table->string('cv_model_version', 64)->nullable();
            }

            if (! Schema::hasColumn('surveys', 'duration_seconds')) {
                $table->decimal('duration_seconds', 8, 2)->nullable();
            }

            if (! Schema::hasColumn('surveys', 'served_at')) {
                $table->timestamp('served_at')->nullable();
            }

            if (! Schema::hasColumn('surveys', 'needs_confirmation')) {
                $table->boolean('needs_confirmation')->default(false);
            }

            if (! Schema::hasColumn('surveys', 'confirmed_at')) {
                $table->timestamp('confirmed_at')->nullable();
            }

            if (! Schema::hasColumn('surveys', 'confirmation_answer')) {
                $table->boolean('confirmation_answer')->nullable();
            }
        });

        Schema::table('surveys', function (Blueprint $table) {

            // Dipakai endpoint /api/cv/pending yang di-polling kiosk
            // setiap 1-2 detik. Tanpa indeks, query ini akan memindai
            // seluruh tabel berkali-kali per menit.
            $table->index(
                ['needs_confirmation', 'confirmed_at', 'counter_code'],
                'surveys_pending_confirmation_index'
            );

            $table->index(['source', 'created_at'], 'surveys_source_created_index');
        });
    }

    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {

            $table->dropIndex('surveys_pending_confirmation_index');
            $table->dropIndex('surveys_source_created_index');

            $table->dropColumn([
                'session_uid',
                'source',
                'counter_code',
                'cv_score',
                'cv_confidence',
                'cv_emotion',
                'cv_distribution',
                'cv_valence',
                'cv_frames_seen',
                'cv_samples_used',
                'cv_effective_samples',
                'cv_components',
                'cv_model_version',
                'duration_seconds',
                'served_at',
                'needs_confirmation',
                'confirmed_at',
                'confirmation_answer',
            ]);
        });
    }
};
