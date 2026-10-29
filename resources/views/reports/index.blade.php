@extends('layouts.admin')

@section('title', 'Laporan Computer Vision')
@section('page-title', 'Laporan Computer Vision')

@push('styles')
<style>
    .summary-card {
        border-radius: 12px;
        padding: 16px 18px;
        background: #fff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,.06);
    }
    .summary-card .label {
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-bottom: 8px;
    }
    .summary-card .value { font-size: 24px; font-weight: 700; line-height: 1; }

    .uid-cell {
        font-family: monospace;
        font-size: 10.5px;
        color: #94a3b8;
        max-width: 160px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
@endpush

@section('content')

<div class="d-flex align-items-start justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h1 class="fs-4 fw-bold mb-1">Laporan Computer Vision</h1>
        <p class="text-muted mb-0" style="font-size:13px">
            Rincian sesi Computer Vision yang hasilnya sudah final
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('reports.pdf', request()->query()) }}" class="btn btn-danger d-flex align-items-center gap-2">
            <i class="bi bi-file-earmark-pdf"></i> PDF
        </a>
        <a href="{{ route('reports.excel', request()->query()) }}" class="btn btn-success d-flex align-items-center gap-2">
            <i class="bi bi-file-earmark-spreadsheet"></i> Excel
        </a>
    </div>
</div>

<!-- Ringkasan mengikuti filter yang sedang aktif -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="summary-card">
            <div class="label">Jumlah Sesi</div>
            <div class="value" style="color:#2563eb">{{ number_format($summary['total']) }}</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="summary-card">
            <div class="label">Rata-rata Rating</div>
            <div class="value" style="color:#f59e0b">
                &#11088; {{ number_format($summary['avg_rating'], 2) }}
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="summary-card">
            <div class="label">Confidence</div>
            <div class="value" style="color:#16a34a">
                {{ number_format($summary['avg_confidence'] * 100, 1) }}%
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="summary-card">
            <div class="label">Durasi Rata-rata</div>
            <div class="value" style="color:#0891b2">
                {{ number_format($summary['avg_duration'], 1) }}<span style="font-size:14px"> dtk</span>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">

            <div class="col-6 col-md-3">
                <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.06em">
                    Tanggal Mulai
                </label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                       class="form-control" style="border-radius:8px;border:1px solid #e2e8f0;font-size:13px">
            </div>

            <div class="col-6 col-md-3">
                <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.06em">
                    Tanggal Akhir
                </label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                       class="form-control" style="border-radius:8px;border:1px solid #e2e8f0;font-size:13px">
            </div>

            <div class="col-6 col-md-2">
                <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.06em">
                    Loket
                </label>
                <select name="counter_code" class="form-select" style="border-radius:8px;border-color:#e2e8f0;font-size:13px">
                    <option value="">Semua</option>
                    @foreach($counters as $counter)
                        <option value="{{ $counter }}" {{ request('counter_code') === $counter ? 'selected' : '' }}>
                            {{ $counter }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-6 col-md-2">
                <label class="form-label" style="font-size:12px;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:.06em">
                    Emosi
                </label>
                <select name="emotion" class="form-select" style="border-radius:8px;border-color:#e2e8f0;font-size:13px">
                    <option value="">Semua</option>
                    @foreach($emotions as $emotion)
                        <option value="{{ $emotion }}" {{ request('emotion') === $emotion ? 'selected' : '' }}>
                            {{ ucfirst($emotion) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-2 d-flex gap-2">
                <button class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                @if(request()->hasAny(['start_date','end_date','counter_code','emotion']))
                    <a href="{{ route('reports.index') }}" class="btn" style="background:#f1f5f9;color:#475569">
                        Reset
                    </a>
                @endif
            </div>

        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Pegawai</th>
                        <th>Loket</th>
                        <th>Rating</th>
                        <th>Emosi</th>
                        <th>Confidence</th>
                        <th>Durasi</th>
                        <th>Status</th>
                        <th>Session UID</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($sessions as $session)
                <tr>
                    <td style="font-size:13px;color:#64748b;white-space:nowrap">
                        {{ $session->finished_at?->format('d M Y, H:i') ?? '-' }}
                    </td>
                    <td class="fw-semibold" style="font-size:13.5px">
                        {{ $session->employee?->name ?? '-' }}
                        <div style="font-size:11.5px;color:#94a3b8;font-weight:400">
                            {{ $session->employee?->position }}
                        </div>
                    </td>
                    <td>
                        <span class="badge" style="background:#eff6ff;color:#2563eb;font-family:monospace">
                            {{ $session->counter_code }}
                        </span>
                    </td>
                    <td>
                        <span style="color:#f59e0b;font-weight:600;white-space:nowrap">
                            &#11088; {{ $session->rating ?? '-' }}
                        </span>
                    </td>
                    <td style="font-size:13px">{{ ucfirst($session->dominant_emotion ?? '-') }}</td>
                    <td style="font-size:13px;font-weight:600;color:{{ $session->isHighConfidence() ? '#16a34a' : '#f59e0b' }}">
                        {{ number_format((float) ($session->confidence ?? 0) * 100, 1) }}%
                    </td>
                    <td style="font-size:13px;color:#64748b;white-space:nowrap">
                        {{ $session->duration_seconds !== null ? number_format((float) $session->duration_seconds, 1).' dtk' : '-' }}
                    </td>
                    <td>
                        @if($session->status === 'confirmed')
                            <span class="badge" style="background:#dcfce7;color:#15803d">Dikonfirmasi</span>
                        @else
                            <span class="badge" style="background:#f1f5f9;color:#475569">Otomatis</span>
                        @endif
                    </td>
                    <td class="uid-cell" title="{{ $session->session_uid }}">
                        {{ $session->session_uid }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted" style="font-size:13px;padding:32px">
                        Tidak ada sesi Computer Vision untuk filter ini.
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($sessions->hasPages())
<div class="mt-4">
    {{ $sessions->links() }}
</div>
@endif

@endsection
