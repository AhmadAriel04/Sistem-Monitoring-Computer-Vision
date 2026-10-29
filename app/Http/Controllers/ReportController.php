<?php

namespace App\Http\Controllers;

use App\Exports\CvSessionExport;
use App\Models\CvSession;
use App\Models\Employee;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Laporan sesi Computer Vision.
 *
 * Seluruh sumber datanya cv_sessions, sama dengan dashboard. Data survey
 * manual dari kiosk tidak lagi muncul di laporan.
 */
class ReportController extends Controller
{
    private const EMOTIONS = ['happy', 'neutral', 'sad', 'angry'];

    public function index(Request $request)
    {
        $filters = $this->validatedFilters($request);

        $sessions = CvSession::filtered($filters)
            ->with('employee:id,name,position')
            ->latest('created_at')
            ->paginate(20)
            ->withQueryString();

        /*
         * Ringkasan dihitung dari query yang sudah difilter, bukan dari
         * halaman yang sedang tampil. Kalau dihitung dari $sessions, angka
         * rata-rata hanya mewakili 20 baris di layar dan akan berubah tiap
         * kali pengguna pindah halaman.
         */
        $summary = [
            'total' => CvSession::filtered($filters)->count(),

            'avg_rating' => round(
                (float) (CvSession::filtered($filters)->avg('rating') ?? 0),
                2
            ),

            'avg_confidence' => round(
                (float) (CvSession::filtered($filters)->avg('confidence') ?? 0),
                4
            ),

            'avg_duration' => round(
                (float) (CvSession::filtered($filters)->avg('duration_seconds') ?? 0),
                1
            ),
        ];

        $counters = Employee::whereNotNull('counter_code')
            ->orderBy('counter_code')
            ->pluck('counter_code');

        $emotions = self::EMOTIONS;

        return view('reports.index', compact(
            'sessions',
            'summary',
            'counters',
            'emotions',
            'filters'
        ));
    }

    public function pdf(Request $request)
    {
        $filters = $this->validatedFilters($request);

        $sessions = CvSession::filtered($filters)
            ->with('employee:id,name,position')
            ->latest('created_at')
            ->get();

        $pdf = Pdf::loadView('reports.pdf', [
            'sessions' => $sessions,
            'filters'  => $filters,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-computer-vision.pdf');
    }

    public function excel(Request $request)
    {
        $this->validatedFilters($request);

        return Excel::download(
            CvSessionExport::fromRequest($request),
            'laporan-computer-vision.xlsx'
        );
    }

    /**
     * Validasi berlaku di ketiga endpoint, bukan hanya di halaman index.
     * Tanpa ini, /reports/pdf?emotion=<apapun> akan diteruskan mentah ke query.
     *
     * @return array<string, mixed>
     */
    private function validatedFilters(Request $request): array
    {
        return $request->validate([
            'start_date'   => ['nullable', 'date'],
            'end_date'     => ['nullable', 'date', 'after_or_equal:start_date'],
            'counter_code' => ['nullable', 'string', 'max:32'],
            'emotion'      => ['nullable', 'string', 'in:' . implode(',', self::EMOTIONS)],
        ]);
    }
}
