<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BSI Survey Monitor</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

    <div class="login-box">

        <!-- Left Section -->
        <div class="left">

            <img src="{{ asset('images/ilustration.png') }}" alt="Logo BSI">

            <h1>BSI Survey Monitor</h1>

            <p>
                Sistem Monitoring Kepuasan Nasabah Berbasis Computer Vision
                untuk mendukung evaluasi kualitas layanan secara real-time.
            </p>

            <div class="feature">
                <div><i class="bi bi-check-circle-fill"></i> Monitoring Kepuasan Nasabah</div>
                <div><i class="bi bi-check-circle-fill"></i> Dashboard Monitoring</div>
                <div><i class="bi bi-check-circle-fill"></i> Laporan Realtime</div>
            </div>

        </div>

        <!-- Right Section -->
        <div class="right">

            <img src="{{ asset('images/bsi.png') }}" class="logo" alt="Logo BSI">

            <h2>Selamat Datang</h2>

            <div class="subtitle">
                Silakan login menggunakan akun Anda
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-group">
                    <i class="bi bi-envelope"></i>

                    <input
                        type="email"
                        name="email"
                        placeholder="Email"
                        value="{{ old('email') }}"
                        required
                    >
                </div>

                @error('email')
                    <div style="color:red; font-size:13px; margin-bottom:10px;">
                        {{ $message }}
                    </div>
                @enderror

                <div class="input-group">
                    <i class="bi bi-lock"></i>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Password"
                        required
                    >

                    <i class="bi bi-eye toggle-password" onclick="togglePassword()"></i>
                </div>

                @error('password')
                    <div style="color:red; font-size:13px; margin-bottom:10px;">
                        {{ $message }}
                    </div>
                @enderror

                <div class="remember">

                    <label>
                        <input type="checkbox" name="remember">
                        Remember Me
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">
                            Lupa Password?
                        </a>
                    @endif

                </div>

                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Masuk
                </button>

                @if (Route::has('register'))
                <div class="register-link">
                    Belum punya akun?
                    <a href="{{ route('register') }}">Daftar sekarang</a>
                </div>
            @endif

            </form>

            <div class="copyright">
                © {{ date('Y') }} BSI Survey Monitor
            </div>

        </div>

    </div>
</body>

</html>