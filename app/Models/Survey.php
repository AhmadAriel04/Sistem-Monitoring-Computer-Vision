<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Survey extends Model
{
    protected $fillable = [

        'employee_id',
        'rating',

        'aspects',
        'comment',

        'ip_address',
        'device',

        'sentiment',

        'customer_name',
        'customer_phone',
        'customer_photo',

        'q1',
        'q2',
        'q3',
        'nps_score',

        /*
        |--------------------------------------------------------------------------
        | Computer Vision
        |--------------------------------------------------------------------------
        */

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

        'cv_session_id',
    ];

    protected $casts = [

        'aspects' => 'array',

        'cv_distribution' => 'array',
        'cv_components' => 'array',

        'q1' => 'boolean',
        'q2' => 'boolean',
        'q3' => 'boolean',

        'needs_confirmation' => 'boolean',
        'confirmation_answer' => 'boolean',

        'cv_score' => 'float',
        'cv_confidence' => 'float',
        'cv_valence' => 'float',

        'cv_effective_samples' => 'float',

        'duration_seconds' => 'float',

        'served_at' => 'datetime',
        'confirmed_at' => 'datetime',
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

    public function cvSession(): BelongsTo
    {
        return $this->belongsTo(
            CvSession::class,
            'cv_session_id',
            'id'
        );
    }
}