<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CvSession;
use App\Models\Employee;

/**
 * Ringkasan dashboard dalam bentuk JSON.
 *
 * Sumber datanya disamakan dengan halaman /dashboard, yaitu tabel
 * cv_sessions. Kalau sistem Kiosk masih memanggil endpoint ini dan
 * mengharapkan angka survey manual, buatkan endpoint terpisah di sisi
 * Kiosk daripada mencampur dua sumber di satu response.
 */
class DashboardApiController extends Controller
{
    public function index()
    {
        $emotions = ['happy', 'neutral', 'sad', 'angry'];

        $dominant = CvSession::valid()
            ->whereNotNull('dominant_emotion')
            ->selectRaw('dominant_emotion, COUNT(*) as total')
            ->groupBy('dominant_emotion')
            ->pluck('total', 'dominant_emotion');

        return response()->json([

            'source' => 'computer_vision',

            'total_sessions' => CvSession::valid()->count(),

            'today_sessions' => CvSession::valid()
                ->whereDate('created_at', today())
                ->count(),

            'pending_confirmation' => CvSession::pendingConfirmation()->count(),

            'rejected_sessions' => CvSession::where('status', 'rejected')->count(),

            'total_employee' => Employee::where('status', true)->count(),

            'average_rating' => round(
                (float) (CvSession::valid()->avg('rating') ?? 0),
                2
            ),

            'average_confidence' => round(
                (float) (CvSession::valid()->avg('confidence') ?? 0),
                4
            ),

            'average_valence' => round(
                (float) (CvSession::valid()->avg('valence') ?? 0),
                3
            ),

            'dominant_emotion' => array_combine(
                $emotions,
                array_map(
                    fn ($emotion) => (int) ($dominant[$emotion] ?? 0),
                    $emotions
                )
            ),
        ]);
    }
}
