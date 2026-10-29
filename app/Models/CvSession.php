<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CvSession extends Model
{
    use HasFactory;

    protected $table = 'cv_sessions';

    protected $fillable = [
        'session_uid',
        'employee_id',
        'counter_code',

        'started_at',
        'ended_at',
        'duration_seconds',

        'rating',
        'score',
        'confidence',

        'dominant_emotion',
        'distribution',
        'valence',

        'frames_seen',
        'samples_used',
        'effective_samples',

        'confidence_components',
        'model_version',

        'status',

        'needs_confirmation',
        'confirmation_answer',
        'confirmed_at',
        'expires_at',

        'source',
    ];

    protected $casts = [

        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'expires_at' => 'datetime',

        'distribution' => 'array',
        'confidence_components' => 'array',

        'duration_seconds' => 'float',
        'score' => 'float',
        'confidence' => 'float',
        'valence' => 'float',
        'effective_samples' => 'float',

        'needs_confirmation' => 'boolean',
        'confirmation_answer' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function employee(): BelongsTo
    {
        return $this->belongsTo(
            Employee::class,
            'employee_id'
        );
    }

    public function survey(): HasOne
    {
        return $this->hasOne(
            Survey::class,
            'cv_session_id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */

    public function requiresConfirmation(): bool
    {
        return $this->needs_confirmation === true
            && $this->confirmation_answer === null;
    }

    public function isConfirmed(): bool
    {
        return $this->confirmation_answer !== null;
    }

    public function isHighConfidence(
        ?float $threshold = null
    ): bool {
        $threshold ??= (float) config(
            'services.cv.confidence_threshold',
            0.60
        );

        return $this->confidence !== null
            && (float) $this->confidence >= $threshold;
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePendingConfirmation(
        Builder $query
    ): Builder {
        return $query
            ->where('needs_confirmation', true)
            ->whereNull('confirmation_answer')
            ->where(function (Builder $query) {
                $query
                    ->whereNull('expires_at')
                    ->orWhere(
                        'expires_at',
                        '>',
                        now()
                    );
            });
    }

    public function scopeCompleted(
        Builder $query
    ): Builder {
        return $query->where(
            'status',
            'completed'
        );
    }

    /**
     * Sesi yang hasilnya sah dipakai untuk analitik.
     *
     * Status 'completed'  = confidence di atas threshold, otomatis sah.
     * Status 'confirmed'  = confidence rendah, tapi sudah dijawab "Ya".
     *
     * Status 'pending_confirmation' dan 'rejected' sengaja dibuang:
     * yang pertama belum final, yang kedua sudah dibatalkan nasabah.
     */
    public function scopeValid(
        Builder $query
    ): Builder {
        return $query->whereIn(
            'status',
            ['completed', 'confirmed']
        );
    }

    /**
     * Waktu selesai yang selalu terisi.
     *
     * ended_at boleh null (CV bisa mengirim tanpa itu), sehingga filter
     * "hari ini" tidak boleh bergantung padanya secara langsung.
     */
    public function getFinishedAtAttribute()
    {
        return $this->ended_at ?? $this->created_at;
    }

    public function scopeForCounter(
        Builder $query,
        string $counterCode
    ): Builder {
        return $query->where(
            'counter_code',
            $counterCode
        );
    }

    /**
     * Dasar seluruh laporan Computer Vision.
     *
     * Satu tempat untuk semua filter supaya tabel di layar, PDF, dan Excel
     * tidak bisa saling berbeda. Sebelumnya tiap endpoint menyusun query
     * sendiri, dan filter tanggal sempat hilang di jalur export.
     *
     * @param array<string, mixed> $filters
     */
    public function scopeFiltered(
        Builder $query,
        array $filters = []
    ): Builder {

        $query->valid();

        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        if (!empty($filters['counter_code'])) {
            $query->where('counter_code', $filters['counter_code']);
        }

        if (!empty($filters['emotion'])) {
            $query->where('dominant_emotion', $filters['emotion']);
        }

        return $query;
    }
}