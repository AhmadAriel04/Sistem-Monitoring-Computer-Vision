<?php

namespace App\Http\Controllers;

use App\Models\CvSession;
use App\Models\Employee;
use Illuminate\Support\Collection;

/**
 * Dashboard monitoring.
 *
 * Seluruh angka pada halaman ini berasal dari tabel `cv_sessions`, yaitu
 * hasil kiriman sistem Computer Vision. Data survey manual dari kiosk
 * (tabel `surveys`) sengaja tidak lagi ditampilkan di sini supaya angka
 * yang dibaca pengguna hanya punya satu sumber dan tidak tercampur.
 */
class DashboardController extends Controller
{
    /**
     * Emosi yang dikenali model, dipakai konsisten di seluruh agregasi.
     */
    private const EMOTIONS = ['happy', 'neutral', 'sad', 'angry'];

    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Ringkasan sesi CV
        |--------------------------------------------------------------------------
        */

        $totalCvSessions = CvSession::valid()->count();

        $todayCvSessions = CvSession::valid()
            ->whereDate('created_at', today())
            ->count();

        $averageCvRating = round(
            (float) (CvSession::valid()->avg('rating') ?? 0),
            2
        );

        $averageCvScore = round(
            (float) (CvSession::valid()->avg('score') ?? 0),
            2
        );

        $averageCvConfidence = round(
            (float) (CvSession::valid()->avg('confidence') ?? 0),
            4
        );

        $averageCvValence = round(
            (float) (CvSession::valid()->avg('valence') ?? 0),
            3
        );

        $averageDuration = round(
            (float) (CvSession::valid()->avg('duration_seconds') ?? 0),
            1
        );

        $totalEmployee = Employee::where('status', true)->count();

        /*
        |--------------------------------------------------------------------------
        | Sesi yang butuh perhatian
        |--------------------------------------------------------------------------
        |
        | pendingCvSessions  : masih menunggu jawaban nasabah di kiosk.
        | rejectedCvSessions : dibatalkan nasabah / kedaluwarsa.
        |
        */

        $pendingCvSessions = CvSession::pendingConfirmation()->count();

        $rejectedCvSessions = CvSession::where('status', 'rejected')->count();

        /*
        |--------------------------------------------------------------------------
        | Grafik sesi harian (14 hari terakhir)
        |--------------------------------------------------------------------------
        |
        | Rentangnya dibatasi supaya sumbu X tetap terbaca ketika data sudah
        | menumpuk berbulan-bulan. Tanggal kosong tetap diisi 0 agar grafik
        | tidak "melompat" dan menyembunyikan hari tanpa aktivitas.
        |
        */

        $startDate = today()->subDays(13);

        $dailyRaw = CvSession::valid()
            ->whereDate('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total, AVG(rating) as avg_rating')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy(fn ($row) => (string) $row->date);

        $chartLabels = [];
        $chartTotals = [];
        $chartRatings = [];

        for ($day = $startDate->copy(); $day->lte(today()); $day->addDay()) {

            $key = $day->toDateString();
            $row = $dailyRaw->get($key);

            $chartLabels[] = $day->format('d M');
            $chartTotals[] = (int) ($row->total ?? 0);
            $chartRatings[] = round((float) ($row->avg_rating ?? 0), 2);
        }

        /*
        |--------------------------------------------------------------------------
        | Distribusi ekspresi
        |--------------------------------------------------------------------------
        |
        | Tiap sesi menyimpan distribusi berupa proporsi 0..1. Rata-rata dari
        | seluruh sesi lalu diubah ke persen. Diproses dalam potongan (chunk)
        | agar tidak memuat seluruh tabel ke memori saat data sudah besar.
        |
        */

        $emotionTotals = array_fill_keys(self::EMOTIONS, 0.0);
        $emotionSessionCount = 0;

        CvSession::valid()
            ->whereNotNull('distribution')
            ->select(['id', 'distribution'])
            ->chunkById(500, function (Collection $sessions) use (
                &$emotionTotals,
                &$emotionSessionCount
            ) {
                foreach ($sessions as $session) {

                    $distribution = is_array($session->distribution)
                        ? $session->distribution
                        : [];

                    if ($distribution === []) {
                        continue;
                    }

                    $emotionSessionCount++;

                    foreach (self::EMOTIONS as $emotion) {
                        $emotionTotals[$emotion] +=
                            (float) ($distribution[$emotion] ?? 0);
                    }
                }
            });

        $cvEmotionChartData = [];

        foreach (self::EMOTIONS as $emotion) {

            $cvEmotionChartData[] = $emotionSessionCount > 0
                ? round(($emotionTotals[$emotion] / $emotionSessionCount) * 100, 2)
                : 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Emosi dominan (jumlah sesi, bukan proporsi)
        |--------------------------------------------------------------------------
        */

        $dominantRaw = CvSession::valid()
            ->whereNotNull('dominant_emotion')
            ->selectRaw('dominant_emotion, COUNT(*) as total')
            ->groupBy('dominant_emotion')
            ->pluck('total', 'dominant_emotion');

        $cvDominantChartData = array_map(
            fn ($emotion) => (int) ($dominantRaw[$emotion] ?? 0),
            self::EMOTIONS
        );

        /*
        |--------------------------------------------------------------------------
        | Sebaran rating CV 1..5
        |--------------------------------------------------------------------------
        */

        $ratingRaw = CvSession::valid()
            ->whereNotNull('rating')
            ->selectRaw('rating, COUNT(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating');

        $cvRatingChartData = [];

        for ($star = 1; $star <= 5; $star++) {
            $cvRatingChartData[] = (int) ($ratingRaw[$star] ?? 0);
        }

        /*
        |--------------------------------------------------------------------------
        | Peringkat pegawai berdasarkan rating CV
        |--------------------------------------------------------------------------
        |
        | withAvg tanpa filter status akan ikut menghitung sesi yang ditolak,
        | jadi relasinya dibatasi memakai scope valid(). Pegawai yang belum
        | punya satu pun sesi dibuang, karena rata-rata null bukan berarti
        | performanya buruk, hanya belum ada datanya.
        |
        */

        $employeeStats = Employee::query()
            ->withAvg(
                ['cvSessions as cv_avg_rating' => fn ($query) => $query->valid()],
                'rating'
            )
            ->withCount(
                ['cvSessions as cv_sessions_count' => fn ($query) => $query->valid()]
            )
            ->get()
            ->filter(fn ($employee) => $employee->cv_avg_rating !== null);

        $bestEmployees = $employeeStats
            ->sortByDesc('cv_avg_rating')
            ->take(5)
            ->values();

        $lowEmployees = $employeeStats
            ->filter(fn ($employee) => (float) $employee->cv_avg_rating <= 3)
            ->sortBy('cv_avg_rating')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Performa per loket
        |--------------------------------------------------------------------------
        */

        $counterStats = CvSession::valid()
            ->selectRaw('counter_code, COUNT(*) as total, AVG(rating) as avg_rating, AVG(confidence) as avg_confidence')
            ->groupBy('counter_code')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Sesi terbaru
        |--------------------------------------------------------------------------
        */

        $latestCvSessions = CvSession::valid()
            ->with('employee:id,name,position')
            ->latest('created_at')
            ->take(8)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalCvSessions',
            'todayCvSessions',
            'averageCvRating',
            'averageCvScore',
            'averageCvConfidence',
            'averageCvValence',
            'averageDuration',
            'totalEmployee',
            'pendingCvSessions',
            'rejectedCvSessions',
            'chartLabels',
            'chartTotals',
            'chartRatings',
            'cvEmotionChartData',
            'cvDominantChartData',
            'cvRatingChartData',
            'bestEmployees',
            'lowEmployees',
            'counterStats',
            'latestCvSessions'
        ));
    }

    /**
     * Tampilan layar besar (TV) di area kantor.
     *
     * Rute /monitor sebelumnya menunjuk ke method yang tidak pernah ada,
     * sehingga selalu menghasilkan error. Sekarang diisi dan sumber
     * datanya disamakan dengan dashboard: murni Computer Vision.
     */
    public function monitor()
    {
        $total = CvSession::valid()->count();

        $today = CvSession::valid()
            ->whereDate('created_at', today())
            ->count();

        $rating = round(
            (float) (CvSession::valid()->avg('rating') ?? 0),
            2
        );

        $confidence = round(
            (float) (CvSession::valid()->avg('confidence') ?? 0),
            4
        );

        $dominantEmotion = CvSession::valid()
            ->whereNotNull('dominant_emotion')
            ->selectRaw('dominant_emotion, COUNT(*) as total')
            ->groupBy('dominant_emotion')
            ->orderByDesc('total')
            ->value('dominant_emotion');

        return view('monitor', compact(
            'total',
            'today',
            'rating',
            'confidence',
            'dominantEmotion'
        ));
    }
}
