<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CvSession;
use App\Models\Employee;
use App\Models\Survey;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CvController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HEALTH
    |--------------------------------------------------------------------------
    */

    public function health(): JsonResponse
    {
        return response()->json([
            'success' => true,

            'service' =>
                'BSI Survey Monitor - Computer Vision API',

            'status' => 'online',

            'timestamp' =>
                now()->toIso8601String(),

            'configuration' => [
                'confidence_threshold' =>
                    (float) config(
                        'services.cv.confidence_threshold',
                        0.60
                    ),

                'confirmation_ttl_minutes' =>
                    (int) config(
                        'services.cv.confirmation_ttl_minutes',
                        3
                    ),
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | STORE SESSION
    |--------------------------------------------------------------------------
    |
    | Menerima hasil satu sesi dari sistem Python Computer Vision.
    |
    */

    public function storeSession(
        Request $request
    ): JsonResponse {

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'session_uid' => [
                'required',
                'string',
                'max:64',
            ],

            'counter_code' => [
                'required',
                'string',
                'max:32',
            ],

            'started_at' => [
                'required',
                'date',
            ],

            'ended_at' => [
                'nullable',
                'date',
                'after_or_equal:started_at',
            ],

            'duration_seconds' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'rating' => [
                'required',
                'integer',
                'between:1,5',
            ],

            'score' => [
                'nullable',
                'numeric',
                'between:1,5',
            ],

            'confidence' => [
                'required',
                'numeric',
                'between:0,1',
            ],

            'dominant_emotion' => [
                'nullable',
                'string',
                'in:happy,neutral,sad,angry',
            ],

            'distribution' => [
                'nullable',
                'array',
            ],

            'valence' => [
                'nullable',
                'numeric',
                'between:-1,1',
            ],

            'frames_seen' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'samples_used' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'effective_samples' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'confidence_components' => [
                'nullable',
                'array',
            ],

            'model_version' => [
                'nullable',
                'string',
                'max:64',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Confidence threshold
        |--------------------------------------------------------------------------
        */

        $threshold = (float) config(
            'services.cv.confidence_threshold',
            0.60
        );

        $confidence = (float) $validated['confidence'];

        /*
        |--------------------------------------------------------------------------
        | Tentukan otomatis
        |--------------------------------------------------------------------------
        |
        | Jangan menerima `needs_confirmation` dari Python.
        | Laravel yang menentukan berdasarkan confidence.
        |
        */

        $needsConfirmation =
            $confidence < $threshold;

        /*
        |--------------------------------------------------------------------------
        | IDEMPOTENCY
        |--------------------------------------------------------------------------
        |
        | Computer Vision mungkin mengirim ulang session jika terjadi network
        | error. Pemeriksaan ini sengaja dijalankan lebih dulu daripada lookup
        | pegawai: kalau pegawainya dinonaktifkan setelah sesi tersimpan,
        | pengiriman ulang tetap harus dijawab 200 duplicate, bukan 422 yang
        | membuat outbox di sisi Python terus mencoba ulang selamanya.
        |
        */

        $existing = CvSession::query()
            ->where(
                'session_uid',
                $validated['session_uid']
            )
            ->with('employee', 'survey')
            ->first();

        if ($existing) {

            return response()->json([

                'success' => true,

                'message' =>
                    'Session sudah pernah diterima.',

                'duplicate' => true,

                'data' => $this->sessionResponse(
                    $existing
                ),

            ], 200);
        }

        /*
        |--------------------------------------------------------------------------
        | Cari pegawai
        |--------------------------------------------------------------------------
        */

        $employee = Employee::query()
            ->where(
                'counter_code',
                $validated['counter_code']
            )
            ->where(
                'status',
                true
            )
            ->first();

        if (!$employee) {

            return response()->json([
                'success' => false,

                'message' =>
                    'Pegawai aktif dengan counter_code tersebut tidak ditemukan.',

                'counter_code' =>
                    $validated['counter_code'],
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN SESSION + SURVEY
        |--------------------------------------------------------------------------
        */

        try {

            $cvSession = DB::transaction(
                function () use (
                    $validated,
                    $employee,
                    $needsConfirmation
                ) {

                    $status = $needsConfirmation
                        ? 'pending_confirmation'
                        : 'completed';

                    $source = 'cv_auto';

                    /*
                    |--------------------------------------------------------------------------
                    | CV SESSION
                    |--------------------------------------------------------------------------
                    */

                    $cvSession = CvSession::create([

                        'session_uid' =>
                            $validated['session_uid'],

                        'employee_id' =>
                            $employee->id,

                        'counter_code' =>
                            $employee->counter_code,

                        'started_at' =>
                            $validated['started_at'],

                        // Dashboard memfilter per hari lewat kolom waktu.
                        // Kalau CV tidak mengirim ended_at, dipakai waktu
                        // server agar sesi tidak hilang dari agregasi.
                        'ended_at' =>
                            $validated['ended_at']
                            ?? now(),

                        'duration_seconds' =>
                            $validated['duration_seconds']
                            ?? null,

                        'rating' =>
                            $validated['rating'],

                        'score' =>
                            $validated['score']
                            ?? null,

                        'confidence' =>
                            $validated['confidence'],

                        'dominant_emotion' =>
                            $validated['dominant_emotion']
                            ?? null,

                        'distribution' =>
                            $validated['distribution']
                            ?? null,

                        'valence' =>
                            $validated['valence']
                            ?? null,

                        'frames_seen' =>
                            $validated['frames_seen']
                            ?? null,

                        'samples_used' =>
                            $validated['samples_used']
                            ?? null,

                        'effective_samples' =>
                            $validated['effective_samples']
                            ?? null,

                        'confidence_components' =>
                            $validated['confidence_components']
                            ?? null,

                        'model_version' =>
                            $validated['model_version']
                            ?? null,

                        'status' =>
                            $status,

                        'needs_confirmation' =>
                            $needsConfirmation,

                        'confirmation_answer' =>
                            null,

                        'confirmed_at' =>
                            null,

                        'expires_at' =>
                            $needsConfirmation
                                ? now()->addMinutes(
                                    (int) config(
                                        'services.cv.confirmation_ttl_minutes',
                                        3
                                    )
                                )
                                : null,

                        'source' =>
                            $source,
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | SURVEY
                    |--------------------------------------------------------------------------
                    */

                    Survey::create([

                        'employee_id' =>
                            $employee->id,

                        'rating' =>
                            $validated['rating'],

                        'source' =>
                            $source,

                        'counter_code' =>
                            $employee->counter_code,

                        'cv_session_id' =>
                            $cvSession->id,

                        'session_uid' =>
                            $validated['session_uid'],

                        'cv_score' =>
                            $validated['score']
                            ?? null,

                        'cv_confidence' =>
                            $validated['confidence'],

                        'cv_emotion' =>
                            $validated['dominant_emotion']
                            ?? null,

                        'cv_distribution' =>
                            $validated['distribution']
                            ?? null,

                        'cv_valence' =>
                            $validated['valence']
                            ?? null,

                        'cv_frames_seen' =>
                            $validated['frames_seen']
                            ?? null,

                        'cv_samples_used' =>
                            $validated['samples_used']
                            ?? null,

                        'cv_effective_samples' =>
                            $validated['effective_samples']
                            ?? null,

                        'cv_components' =>
                            $validated['confidence_components']
                            ?? null,

                        'cv_model_version' =>
                            $validated['model_version']
                            ?? null,

                        'duration_seconds' =>
                            $validated['duration_seconds']
                            ?? null,

                        'served_at' =>
                            $validated['started_at'],

                        'needs_confirmation' =>
                            $needsConfirmation,

                        'confirmed_at' =>
                            null,

                        'confirmation_answer' =>
                            null,
                    ]);

                    return $cvSession;
                }
            );

            /*
            |--------------------------------------------------------------------------
            | LOG
            |--------------------------------------------------------------------------
            */

            Log::info(
                'CV session berhasil disimpan.',
                [
                    'session_uid' =>
                        $cvSession->session_uid,

                    'employee_id' =>
                        $cvSession->employee_id,

                    'confidence' =>
                        $cvSession->confidence,

                    'rating' =>
                        $cvSession->rating,

                    'needs_confirmation' =>
                        $cvSession->needs_confirmation,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            $cvSession->load(
                'employee',
                'survey'
            );

            return response()->json([

                'success' => true,

                'message' =>
                    $needsConfirmation
                        ? 'CV session disimpan dan membutuhkan konfirmasi.'
                        : 'CV session berhasil disimpan otomatis.',

                'duplicate' => false,

                'data' =>
                    $this->sessionResponse(
                        $cvSession
                    ),

            ], 201);

        } catch (\Throwable $e) {

            Log::error(
                'Gagal menyimpan CV session.',
                [
                    'session_uid' =>
                        $validated['session_uid']
                        ?? null,

                    'error' =>
                        $e->getMessage(),
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Race condition / duplicate
            |--------------------------------------------------------------------------
            */

            $existing = CvSession::query()
                ->where(
                    'session_uid',
                    $validated['session_uid']
                )
                ->with('employee', 'survey')
                ->first();

            if ($existing) {

                return response()->json([

                    'success' => true,

                    'message' =>
                        'Session sudah pernah diterima.',

                    'duplicate' => true,

                    'data' =>
                        $this->sessionResponse(
                            $existing
                        ),

                ], 200);
            }

            return response()->json([

                'success' => false,

                'message' =>
                    'Gagal menyimpan CV session.',

                'error' =>
                    config('app.debug')
                        ? $e->getMessage()
                        : null,

            ], 500);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PENDING
    |--------------------------------------------------------------------------
    |
    | Dipanggil oleh sistem Kiosk.
    |
    */

    public function pending(
        Request $request
    ): JsonResponse {

        $validated = $request->validate([

            'counter_code' => [
                'required',
                'string',
                'max:32',
            ],

        ]);

        $session = CvSession::query()

            ->forCounter(
                $validated['counter_code']
            )

            ->pendingConfirmation()

            ->with('employee')

            ->latest('created_at')

            ->first();

        /*
        |--------------------------------------------------------------------------
        | Tidak ada pending
        |--------------------------------------------------------------------------
        */

        if (!$session) {

            return response()->json([

                'success' => true,

                'pending' => false,

                'data' => null,

            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Ada pending
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' => true,

            'pending' => true,

            'data' => [

                'session_uid' =>
                    $session->session_uid,

                'question' =>
                    'Apakah pelayanan kami memuaskan?',

                'rating' =>
                    $session->rating,

                'confidence' =>
                    $session->confidence,

                'emotion' =>
                    $session->dominant_emotion,

                'employee' => [

                    'id' =>
                        $session->employee?->id,

                    'name' =>
                        $session->employee?->name,

                    'position' =>
                        $session->employee?->position,

                ],

                'expires_at' =>
                    $session->expires_at
                        ?->toIso8601String(),

            ],

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CONFIRM
    |--------------------------------------------------------------------------
    |
    | Dipanggil Kiosk ketika nasabah memilih:
    |
    | true  = Ya
    | false = Tidak
    |
    */

    public function confirm(
        Request $request,
        string $uid
    ): JsonResponse {

        $validated = $request->validate([

            'answer' => [
                'required',
                'boolean',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Cari session
        |--------------------------------------------------------------------------
        */

        $session = CvSession::query()
            ->where(
                'session_uid',
                $uid
            )
            ->with('survey')
            ->first();

        if (!$session) {

            return response()->json([

                'success' => false,

                'message' =>
                    'CV session tidak ditemukan.',

            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan memang pending
        |--------------------------------------------------------------------------
        */

        if (!$session->needs_confirmation) {

            /*
             * Dibedakan supaya kiosk tahu ini bukan kesalahan pemakaian:
             * sesi memang sudah dijawab (mungkin double-tap atau retry),
             * jadi jawabannya cukup dikembalikan apa adanya.
             */
            if ($session->isConfirmed()) {

                return response()->json([

                    'success' => true,

                    'message' =>
                        'Session ini sudah dikonfirmasi sebelumnya.',

                    'duplicate' => true,

                    'data' => [

                        'session_uid' =>
                            $session->session_uid,

                        'answer' =>
                            $session->confirmation_answer,

                        'status' =>
                            $session->status,

                    ],

                ], 200);
            }

            return response()->json([

                'success' => false,

                'message' =>
                    'Session ini tidak membutuhkan konfirmasi.',

            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Cek expiry
        |--------------------------------------------------------------------------
        */

        if (
            $session->expires_at &&
            $session->expires_at->isPast()
        ) {

            /*
             * Tandai expired/rejected.
             */

            DB::transaction(function () use ($session) {

                $session->update([

                    'status' =>
                        'rejected',

                    'needs_confirmation' =>
                        false,

                    'source' =>
                        'cv_expired',

                ]);

                Survey::query()
                    ->where(
                        'cv_session_id',
                        $session->id
                    )
                    ->delete();
            });

            return response()->json([

                'success' => false,

                'message' =>
                    'Konfirmasi sudah kedaluwarsa.',

            ], 410);
        }

        /*
        |--------------------------------------------------------------------------
        | Proses jawaban
        |--------------------------------------------------------------------------
        */

        $answer = (bool) $validated['answer'];

        DB::transaction(
            function () use (
                $session,
                $answer
            ) {

                /*
                |--------------------------------------------------------------------------
                | JAWABAN YA
                |--------------------------------------------------------------------------
                */

                if ($answer) {

                    $session->update([

                        'confirmation_answer' =>
                            true,

                        'confirmed_at' =>
                            now(),

                        'needs_confirmation' =>
                            false,

                        'status' =>
                            'confirmed',

                        'source' =>
                            'cv_confirmed',

                    ]);

                    Survey::query()

                        ->where(
                            'cv_session_id',
                            $session->id
                        )

                        ->update([

                            'source' =>
                                'cv_confirmed',

                            'needs_confirmation' =>
                                false,

                            'confirmed_at' =>
                                now(),

                            'confirmation_answer' =>
                                true,

                        ]);

                }

                /*
                |--------------------------------------------------------------------------
                | JAWABAN TIDAK
                |--------------------------------------------------------------------------
                */

                else {

                    $session->update([

                        'confirmation_answer' =>
                            false,

                        'confirmed_at' =>
                            now(),

                        'needs_confirmation' =>
                            false,

                        'status' =>
                            'rejected',

                        'source' =>
                            'cv_rejected',

                    ]);

                    Survey::query()

                        ->where(
                            'cv_session_id',
                            $session->id
                        )

                        ->delete();
                }
            }
        );

        return response()->json([

            'success' => true,

            'message' =>
                $answer
                    ? 'Konfirmasi diterima. Hasil CV disimpan.'
                    : 'Hasil CV dibatalkan.',

            'data' => [

                'session_uid' =>
                    $session->session_uid,

                'answer' =>
                    $answer,

                'status' =>
                    $answer
                        ? 'confirmed'
                        : 'rejected',

            ],

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PRIVATE RESPONSE FORMAT
    |--------------------------------------------------------------------------
    */

    private function sessionResponse(
        CvSession $session
    ): array {

        return [

            'id' =>
                $session->id,

            'session_uid' =>
                $session->session_uid,

            'employee_id' =>
                $session->employee_id,

            'counter_code' =>
                $session->counter_code,

            'started_at' =>
                $session->started_at
                    ?->toIso8601String(),

            'ended_at' =>
                $session->ended_at
                    ?->toIso8601String(),

            'duration_seconds' =>
                $session->duration_seconds,

            'rating' =>
                $session->rating,

            'score' =>
                $session->score,

            'confidence' =>
                $session->confidence,

            'dominant_emotion' =>
                $session->dominant_emotion,

            'distribution' =>
                $session->distribution,

            'valence' =>
                $session->valence,

            'frames_seen' =>
                $session->frames_seen,

            'samples_used' =>
                $session->samples_used,

            'effective_samples' =>
                $session->effective_samples,

            'confidence_components' =>
                $session->confidence_components,

            'model_version' =>
                $session->model_version,

            'status' =>
                $session->status,

            'needs_confirmation' =>
                $session->needs_confirmation,

            'confirmation_answer' =>
                $session->confirmation_answer,

            'confirmed_at' =>
                $session->confirmed_at
                    ?->toIso8601String(),

            'expires_at' =>
                $session->expires_at
                    ?->toIso8601String(),

            'source' =>
                $session->source,

            'employee' =>
                $session->relationLoaded('employee')
                    ? [
                        'id' =>
                            $session->employee?->id,

                        'name' =>
                            $session->employee?->name,

                        'position' =>
                            $session->employee?->position,

                        'counter_code' =>
                            $session->employee?->counter_code,
                    ]
                    : null,

        ];
    }
}