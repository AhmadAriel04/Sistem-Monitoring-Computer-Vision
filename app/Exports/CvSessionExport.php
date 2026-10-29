<?php

namespace App\Exports;

use App\Models\CvSession;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

/**
 * Export laporan sesi Computer Vision ke Excel.
 *
 * Sumber datanya sengaja tabel cv_sessions, bukan surveys, supaya isi file
 * persis sama dengan yang tampil di halaman laporan dan dashboard. Kalau
 * mengambil dari surveys, baris hasil kiosk manual akan ikut terbawa.
 */
class CvSessionExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        private array $filters = []
    ) {
    }

    /**
     * Dibuat dari Request supaya controller tidak perlu membongkar
     * parameter satu per satu di tiap endpoint.
     */
    public static function fromRequest(Request $request): self
    {
        return new self($request->only([
            'start_date',
            'end_date',
            'counter_code',
            'emotion',
        ]));
    }

    public function collection()
    {
        return CvSession::filtered($this->filters)
            ->with('employee')
            ->latest('created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Pegawai',
            'Jabatan',
            'Loket',
            'Rating',
            'Skor',
            'Emosi Dominan',
            'Confidence',
            'Valence',
            'Durasi (detik)',
            'Status',
            'Sumber',
            'Versi Model',
            'Session UID',
        ];
    }

    public function map($session): array
    {
        return [
            $session->finished_at?->format('Y-m-d H:i:s'),
            $session->employee?->name ?? '-',
            $session->employee?->position ?? '-',
            $session->counter_code,
            $session->rating,
            $session->score !== null
                ? number_format((float) $session->score, 2)
                : '-',
            $session->dominant_emotion ?? '-',
            $session->confidence !== null
                ? number_format((float) $session->confidence * 100, 1) . '%'
                : '-',
            $session->valence !== null
                ? number_format((float) $session->valence, 3)
                : '-',
            $session->duration_seconds !== null
                ? number_format((float) $session->duration_seconds, 1)
                : '-',
            $session->status,
            $session->source,
            $session->model_version ?? '-',
            $session->session_uid,
        ];
    }
}
