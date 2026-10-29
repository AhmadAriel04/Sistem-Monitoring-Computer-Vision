<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>BSI Survey Monitor</title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">

    <div class="min-h-screen flex">

        {{-- LEFT --}}
        <div
            class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-cyan-900 via-teal-700 to-cyan-500">

            <div class="absolute inset-0 bg-black/10"></div>

            <div class="relative z-10 flex flex-col justify-center px-16 text-white">

                <img src="{{ asset('images/bsi.png') }}"
                    class="h-16 w-auto mb-10">

                <h1 class="text-5xl font-extrabold mb-6">
                    BSI Survey Monitor
                </h1>

                <p class="text-xl text-white/90 leading-9 mb-12">
                    Sistem monitoring survey kepuasan nasabah
                    untuk meningkatkan kualitas pelayanan
                    secara real-time.
                </p>

                <div class="space-y-8">

                    <div class="flex gap-5">

                        <div
                            class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center">

                            <i class="bi bi-bar-chart-fill text-2xl"></i>

                        </div>

                        <div>

                            <h3 class="font-bold text-xl">
                                Dashboard Real-Time
                            </h3>

                            <p class="text-white/80">
                                Monitoring hasil survey secara langsung.
                            </p>

                        </div>

                    </div>

                    <div class="flex gap-5">

                        <div
                            class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center">

                            <i class="bi bi-graph-up-arrow text-2xl"></i>

                        </div>

                        <div>

                            <h3 class="font-bold text-xl">
                                Analisis Data
                            </h3>

                            <p class="text-white/80">
                                Statistik lengkap dalam bentuk grafik.
                            </p>

                        </div>

                    </div>

                    <div class="flex gap-5">

                        <div
                            class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center">

                            <i class="bi bi-shield-lock-fill text-2xl"></i>

                        </div>

                        <div>

                            <h3 class="font-bold text-xl">
                                Aman & Terpercaya
                            </h3>

                            <p class="text-white/80">
                                Login menggunakan autentikasi Laravel.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Background Decoration --}}
            <div
                class="absolute -bottom-20 -right-20 w-96 h-96 rounded-full bg-white/10 blur-3xl"></div>

            <div
                class="absolute top-0 left-0 w-80 h-80 rounded-full bg-cyan-300/10 blur-3xl"></div>

        </div>

        {{-- RIGHT --}}
        <div
            class="flex-1 flex justify-center items-center bg-gradient-to-br from-slate-100 via-white to-slate-200 px-5">

            {{ $slot }}

        </div>

    </div>

</body>

</html>