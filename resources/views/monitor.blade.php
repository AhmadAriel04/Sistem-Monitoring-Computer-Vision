<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="30">

    <title>BSI Monitor</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            color: #f8fafc;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wrap { text-align: center; padding: 40px 20px; }

        h1 { font-size: 40px; margin: 0 0 8px; letter-spacing: .02em; }

        .sub {
            font-size: 14px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .12em;
            margin-bottom: 48px;
        }

        .grid {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            justify-content: center;
        }

        .tile {
            background: #1e293b;
            border-radius: 18px;
            padding: 28px 44px;
            min-width: 220px;
        }

        .tile .label {
            font-size: 12px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .1em;
            margin-bottom: 12px;
        }

        .tile .value { font-size: 52px; font-weight: 700; line-height: 1; }

        .amber { color: #fbbf24; }
        .green { color: #4ade80; }
        .blue  { color: #60a5fa; }
    </style>
</head>

<body>

    <div class="wrap">

        <h1>BSI KCP BALARAJA 3</h1>
        <div class="sub">Computer Vision Monitoring</div>

        <div class="grid">

            <div class="tile">
                <div class="label">Total Sesi CV</div>
                <div class="value blue">{{ number_format($total) }}</div>
            </div>

            <div class="tile">
                <div class="label">Sesi Hari Ini</div>
                <div class="value green">{{ number_format($today) }}</div>
            </div>

            <div class="tile">
                <div class="label">Rata-rata Rating</div>
                <div class="value amber">&#11088; {{ number_format($rating, 2) }}</div>
            </div>

            <div class="tile">
                <div class="label">Confidence</div>
                <div class="value green">{{ number_format($confidence * 100, 1) }}%</div>
            </div>

            <div class="tile">
                <div class="label">Ekspresi Dominan</div>
                <div class="value blue" style="font-size:38px">
                    {{ ucfirst($dominantEmotion ?? '-') }}
                </div>
            </div>

        </div>

    </div>

</body>

</html>
