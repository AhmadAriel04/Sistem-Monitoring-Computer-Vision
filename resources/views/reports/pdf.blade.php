<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Computer Vision</title>

    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; }

        h2 { text-align: center; margin-bottom: 4px; }

        .subtitle {
            text-align: center;
            font-size: 10px;
            color: #555;
            margin-bottom: 4px;
        }

        .meta {
            text-align: center;
            font-size: 9px;
            color: #777;
            margin-bottom: 16px;
        }

        table { width: 100%; border-collapse: collapse; }

        table, th, td { border: 1px solid #999; }

        th, td { padding: 5px; text-align: left; }

        th { background-color: #f2f2f2; }

        td.num { text-align: right; }

        .uid { font-size: 8px; color: #666; }

        .empty { text-align: center; padding: 20px; color: #777; }
    </style>
</head>
<body>

    <h2>Laporan Computer Vision</h2>
    <div class="subtitle">BSI KCP Balaraja 3 &mdash; Customer Satisfaction Monitoring</div>

    <div class="meta">
        @php
            $periode = [];
            if (!empty($filters['start_date'])) { $periode[] = 'dari ' . $filters['start_date']; }
            if (!empty($filters['end_date']))   { $periode[] = 'sampai ' . $filters['end_date']; }
        @endphp

        Periode: {{ $periode ? implode(' ', $periode) : 'seluruh data' }}
        @if(!empty($filters['counter_code'])) &middot; Loket: {{ $filters['counter_code'] }} @endif
        @if(!empty($filters['emotion'])) &middot; Emosi: {{ ucfirst($filters['emotion']) }} @endif
        &middot; Total {{ $sessions->count() }} sesi
        &middot; Dicetak {{ now()->format('d/m/Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
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
                    <td class="num">{{ $loop->iteration }}</td>
                    <td>{{ $session->finished_at?->format('d/m/Y H:i') ?? '-' }}</td>
                    <td>{{ $session->employee?->name ?? '-' }}</td>
                    <td>{{ $session->counter_code }}</td>
                    <td class="num">{{ $session->rating ?? '-' }}</td>
                    <td>{{ ucfirst($session->dominant_emotion ?? '-') }}</td>
                    <td class="num">
                        {{ $session->confidence !== null ? number_format((float) $session->confidence * 100, 1).'%' : '-' }}
                    </td>
                    <td class="num">
                        {{ $session->duration_seconds !== null ? number_format((float) $session->duration_seconds, 1) : '-' }}
                    </td>
                    <td>{{ $session->status === 'confirmed' ? 'Dikonfirmasi' : 'Otomatis' }}</td>
                    <td class="uid">{{ $session->session_uid }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="empty">
                        Tidak ada sesi Computer Vision untuk filter ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
