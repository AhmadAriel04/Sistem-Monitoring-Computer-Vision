<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code — BSI Survey</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .qr-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,.1);
            padding: 48px 40px;
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        .qr-card .icon {
            width: 60px; height: 60px;
            background: #eff6ff;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px;
        }

        .qr-card h2 {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .qr-card p {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 32px;
        }

        .qr-wrapper {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            display: inline-block;
            margin-bottom: 24px;
        }

        .qr-url {
            font-size: 12px;
            color: #64748b;
            background: #f1f5f9;
            border-radius: 8px;
            padding: 8px 12px;
            word-break: break-all;
            border: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>

    <div class="qr-card">
        <div class="icon">📱</div>
        <h2>Scan untuk Survey</h2>
        <p>Arahkan kamera ke QR Code di bawah ini untuk mengisi survey kepuasan nasabah BSI.</p>

        <div class="qr-wrapper">
            {!! QrCode::size(260)->generate($kioskUrl) !!}
        </div>

        <div class="qr-url">{{ $kioskUrl }}</div>
    </div>

</body>
</html>
