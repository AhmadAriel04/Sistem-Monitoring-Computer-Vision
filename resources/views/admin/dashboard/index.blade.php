@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .stat-card-blue   { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color:#fff; }
    .stat-card-green  { background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); color:#fff; }
    .stat-card-amber  { background: linear-gradient(135deg, #d97706 0%, #b45309 100%); color:#fff; }
    .stat-card-cyan   { background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%); color:#fff; }

    .metric-card {
        border-radius: 12px;
        padding: 20px;
        background: #fff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,.06);
        text-align: center;
    }

    .metric-card .metric-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-bottom: 10px;
    }

    .metric-card .metric-value {
        font-size: 28px;
        font-weight: 700;
        line-height: 1;
    }

    .metric-card .metric-sub {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 6px;
    }

    .section-title {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-bottom: 16px;
        margin-top: 8px;
    }

    canvas { max-height: 260px; }

    .employee-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .employee-row:last-child { border-bottom: none; }

    .emp-rank {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: #eff6ff;
        color: #2563eb;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 700;
        flex-shrink: 0;
    }

    .emp-rank.gold   { background: #fef3c7; color: #b45309; }
    .emp-rank.silver { background: #f1f5f9; color: #64748b; }
    .emp-rank.bronze { background: #fef3c7; color: #92400e; }

    .emp-info { flex: 1; min-width: 0; }
    .emp-name { font-size: 13.5px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .emp-pos  { font-size: 11.5px; color: #64748b; }

    .emp-rating {
        font-size: 13px;
        font-weight: 600;
        color: #f59e0b;
        text-align: right;
    }

    .emp-rating small { display:block; color:#94a3b8; font-weight:500; }

    .cv-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        border-radius: 999px;
        background: #eff6ff;
        color: #2563eb;
        font-size: 11px;
        font-weight: 700;
    }

    .cv-session-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .cv-session-row:last-child { border-bottom: none; }

    .cv-session-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .cv-session-info { flex: 1; min-width: 0; }
    .cv-session-name { font-size: 13px; font-weight: 600; }
    .cv-session-meta { font-size: 11px; color: #64748b; }
    .cv-session-uid {
        font-size: 10px;
        color: #94a3b8;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 250px;
    }

    .cv-confidence {
        font-size: 12px;
        font-weight: 700;
        color: #16a34a;
    }
    .cv-confidence.low { color: #f59e0b; }
</style>
@endpush

@section('content')

<div class="mb-4 d-flex align-items-start justify-content-between flex-wrap gap-2">
    <div>
        <h1 class="fs-4 fw-bold mb-1">Computer Vision Monitoring</h1>
        <p class="text-muted mb-0" style="font-size:13px">
            Seluruh angka pada halaman ini berasal dari sesi Computer Vision
        </p>
    </div>
    <span class="cv-badge">
        <i class="bi bi-camera-video"></i> Sumber data: Computer Vision
    </span>
</div>

<!-- ── Statistik Utama ─────────────────────────────── -->
<div class="row g-3 mb-4">

    <div class="col-6 col-md-3">
        <div class="card stat-card stat-card-blue">
            <div class="card-body py-3">
                <div class="stat-label">Total Sesi CV</div>
                <div class="stat-value">{{ number_format($totalCvSessions) }}</div>
                <i class="bi bi-camera-video stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card stat-card-green">
            <div class="card-body py-3">
                <div class="stat-label">Sesi Hari Ini</div>
                <div class="stat-value">{{ number_format($todayCvSessions) }}</div>
                <i class="bi bi-calendar-check stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card stat-card-amber">
            <div class="card-body py-3">
                <div class="stat-label">Menunggu Konfirmasi</div>
                <div class="stat-value">{{ number_format($pendingCvSessions) }}</div>
                <i class="bi bi-hourglass-split stat-icon"></i>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card stat-card stat-card-cyan">
            <div class="card-body py-3">
                <div class="stat-label">Pegawai Aktif</div>
                <div class="stat-value">{{ number_format($totalEmployee) }}</div>
                <i class="bi bi-people stat-icon"></i>
            </div>
        </div>
    </div>

</div>

<!-- ── Metrik Computer Vision ──────────────────────── -->
<p class="section-title">Metrik Computer Vision</p>
<div class="row g-3 mb-4">

    <div class="col-6 col-md-3">
        <div class="metric-card h-100">
            <div class="metric-label">Rata-rata Rating</div>
            <div class="metric-value" style="color:#f59e0b">
                &#11088; {{ number_format($averageCvRating, 2) }}
            </div>
            <div class="metric-sub">
                Skor kontinu {{ number_format($averageCvScore, 2) }}
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="metric-card h-100">
            <div class="metric-label">Confidence Rata-rata</div>
            <div class="metric-value" style="color:#16a34a">
                {{ number_format($averageCvConfidence * 100, 1) }}%
            </div>
            <div class="metric-sub">
                Ambang batas
                {{ number_format(config('services.cv.confidence_threshold') * 100, 0) }}%
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="metric-card h-100">
            <div class="metric-label">Valence Rata-rata</div>
            <div class="metric-value" style="color:{{ $averageCvValence >= 0 ? '#2563eb' : '#ef4444' }}">
                {{ number_format($averageCvValence, 3) }}
            </div>
            <div class="metric-sub">Rentang &minus;1 sampai 1</div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="metric-card h-100">
            @if($rejectedCvSessions > 0)
                <div class="metric-label" style="color:#b91c1c">Sesi Dibatalkan</div>
                <div class="metric-value" style="color:#ef4444">
                    {{ number_format($rejectedCvSessions) }}
                </div>
            @else
                <div class="metric-label" style="color:#15803d">Sesi Dibatalkan</div>
                <div class="metric-value" style="color:#22c55e">0</div>
            @endif
            <div class="metric-sub">
                Durasi rata-rata {{ number_format($averageDuration, 1) }} dtk
            </div>
        </div>
    </div>

</div>

<!-- ── Charts + Sidebar ───────────────────────────── -->
<div class="row g-4">

    <div class="col-lg-8">

        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-bar-chart me-2 text-primary"></i>
                Sesi Computer Vision Harian (14 hari terakhir)
            </div>
            <div class="card-body">
                <canvas id="cvDailyChart"></canvas>
            </div>
        </div>

        <div class="row g-4">

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-emoji-smile me-2 text-success"></i>
                        Distribusi Ekspresi (rata-rata %)
                    </div>
                    <div class="card-body">
                        <canvas id="cvEmotionChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <i class="bi bi-pie-chart me-2 text-info"></i>
                        Emosi Dominan (jumlah sesi)
                    </div>
                    <div class="card-body">
                        <canvas id="cvDominantChart"></canvas>
                    </div>
                </div>
            </div>

        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-bar-chart-steps me-2 text-warning"></i>
                Sebaran Rating Computer Vision
            </div>
            <div class="card-body">
                <canvas id="cvRatingChart"></canvas>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <i class="bi bi-diagram-3 me-2 text-primary"></i>
                Performa per Loket
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Loket</th>
                                <th>Jumlah Sesi</th>
                                <th>Rata-rata Rating</th>
                                <th>Confidence</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($counterStats as $counter)
                            <tr>
                                <td class="fw-semibold">{{ $counter->counter_code }}</td>
                                <td>{{ number_format($counter->total) }}</td>
                                <td>&#11088; {{ number_format((float) $counter->avg_rating, 2) }}</td>
                                <td>{{ number_format((float) $counter->avg_confidence * 100, 1) }}%</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted" style="font-size:13px">
                                    Belum ada data loket.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Side panels -->
    <div class="col-lg-4">

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-trophy text-warning"></i>
                Top 5 Pegawai (Rating CV)
            </div>
            <div class="card-body">
                @forelse($bestEmployees as $employee)
                <div class="employee-row">
                    <div class="emp-rank {{ $loop->iteration === 1 ? 'gold' : ($loop->iteration === 2 ? 'silver' : ($loop->iteration === 3 ? 'bronze' : '')) }}">
                        {{ $loop->iteration }}
                    </div>
                    <div class="emp-info">
                        <div class="emp-name">{{ $employee->name }}</div>
                        <div class="emp-pos">{{ $employee->position }}</div>
                    </div>
                    <div class="emp-rating">
                        &#11088; {{ number_format((float) $employee->cv_avg_rating, 2) }}
                        <small>{{ $employee->cv_sessions_count }} sesi</small>
                    </div>
                </div>
                @empty
                <p class="text-muted text-center mb-0" style="font-size:13px">
                    Belum ada sesi Computer Vision.
                </p>
                @endforelse
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header" style="background:#fef2f2 !important;color:#b91c1c">
                <i class="bi bi-exclamation-triangle me-2"></i>Perlu Evaluasi
            </div>
            <div class="card-body">
                @forelse($lowEmployees as $employee)
                <div class="employee-row">
                    <div class="emp-info">
                        <div class="emp-name">{{ $employee->name }}</div>
                        <div class="emp-pos">{{ $employee->cv_sessions_count }} sesi</div>
                    </div>
                    <span class="badge bg-danger">
                        {{ number_format((float) $employee->cv_avg_rating, 2) }}
                    </span>
                </div>
                @empty
                <p class="text-muted text-center mb-0" style="font-size:13px">
                    Tidak ada pegawai bermasalah &#127881;
                </p>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="bi bi-camera-video me-2 text-info"></i>
                Sesi CV Terbaru
            </div>
            <div class="card-body">
                @forelse($latestCvSessions as $session)
                    <div class="cv-session-row">
                        <div class="cv-session-icon">
                            <i class="bi bi-person-video3"></i>
                        </div>

                        <div class="cv-session-info">
                            <div class="cv-session-name">
                                {{ $session->employee?->name ?? 'Pegawai tidak diketahui' }}
                            </div>
                            <div class="cv-session-meta">
                                {{ ucfirst($session->dominant_emotion ?? '-') }}
                                &middot; {{ $session->counter_code }}
                                &middot; Rating {{ $session->rating ?? '-' }}
                            </div>
                            <div class="cv-session-uid" title="{{ $session->session_uid }}">
                                {{ $session->session_uid }}
                            </div>
                        </div>

                        <div class="text-end">
                            <div class="cv-confidence {{ $session->isHighConfidence() ? '' : 'low' }}">
                                {{ number_format((float) ($session->confidence ?? 0) * 100, 1) }}%
                            </div>
                            <small class="text-muted">
                                {{ $session->finished_at?->diffForHumans() ?? '-' }}
                            </small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center mb-0" style="font-size:13px">
                        Belum ada sesi Computer Vision.
                    </p>
                @endforelse
            </div>
        </div>

    </div>

</div>

<div
    id="chart-data"
    data-labels='@json($chartLabels)'
    data-totals='@json($chartTotals)'
    data-ratings='@json($chartRatings)'
    data-emotion='@json($cvEmotionChartData)'
    data-dominant='@json($cvDominantChartData)'
    data-rating-dist='@json($cvRatingChartData)'
></div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const cd = document.getElementById('chart-data');

    if (!cd) {
        return;
    }

    const labels      = JSON.parse(cd.dataset.labels);
    const totals      = JSON.parse(cd.dataset.totals);
    const ratings     = JSON.parse(cd.dataset.ratings);
    const emotion     = JSON.parse(cd.dataset.emotion);
    const dominant    = JSON.parse(cd.dataset.dominant);
    const ratingDist  = JSON.parse(cd.dataset.ratingDist);

    const emotionLabels = ['Happy', 'Neutral', 'Sad', 'Angry'];
    const emotionColors = ['#22c55e', '#f59e0b', '#3b82f6', '#ef4444'];

    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { labels: { font: { family: 'Inter', size: 12 }, padding: 16 } }
        }
    };

    const axisDefaults = {
        x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 11 } } },
        y: { grid: { color: '#f1f5f9' }, ticks: { font: { family: 'Inter', size: 11 } } }
    };

    new Chart(document.getElementById('cvDailyChart'), {
        data: {
            labels,
            datasets: [
                {
                    type: 'bar',
                    label: 'Jumlah Sesi',
                    data: totals,
                    backgroundColor: 'rgba(37,99,235,.8)',
                    borderRadius: 6,
                    borderSkipped: false,
                    yAxisID: 'y'
                },
                {
                    type: 'line',
                    label: 'Rata-rata Rating',
                    data: ratings,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245,158,11,.15)',
                    tension: .3,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            ...chartDefaults,
            scales: {
                x: axisDefaults.x,
                y: {
                    ...axisDefaults.y,
                    beginAtZero: true,
                    position: 'left'
                },
                y1: {
                    beginAtZero: true,
                    max: 5,
                    position: 'right',
                    grid: { drawOnChartArea: false },
                    ticks: { font: { family: 'Inter', size: 11 } }
                }
            }
        }
    });

    new Chart(document.getElementById('cvEmotionChart'), {
        type: 'doughnut',
        data: {
            labels: emotionLabels,
            datasets: [{
                data: emotion,
                backgroundColor: emotionColors,
                borderWidth: 0,
                hoverOffset: 6
            }]
        },
        options: { ...chartDefaults, cutout: '65%' }
    });

    new Chart(document.getElementById('cvDominantChart'), {
        type: 'doughnut',
        data: {
            labels: emotionLabels,
            datasets: [{
                data: dominant,
                backgroundColor: emotionColors,
                borderWidth: 0,
                hoverOffset: 6
            }]
        },
        options: { ...chartDefaults, cutout: '65%' }
    });

    new Chart(document.getElementById('cvRatingChart'), {
        type: 'bar',
        data: {
            labels: ['1 Bintang', '2 Bintang', '3 Bintang', '4 Bintang', '5 Bintang'],
            datasets: [{
                label: 'Jumlah Sesi',
                data: ratingDist,
                backgroundColor: [
                    'rgba(239,68,68,.8)',
                    'rgba(249,115,22,.8)',
                    'rgba(245,158,11,.8)',
                    'rgba(132,204,22,.8)',
                    'rgba(34,197,94,.8)'
                ],
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            ...chartDefaults,
            plugins: { legend: { display: false } },
            scales: {
                x: axisDefaults.x,
                y: { ...axisDefaults.y, beginAtZero: true }
            }
        }
    });

});
</script>
@endpush
