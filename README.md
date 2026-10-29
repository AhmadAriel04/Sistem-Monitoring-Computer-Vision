# Sistem Monitor Survey - BSI

Aplikasi admin (perlu login) untuk mengelola kategori/pegawai, melihat
dashboard, dan mengunduh laporan dari data survey yang dikirim
Sistem Survey (Kiosk).

Bagian dari 2 sistem yang saling terhubung lewat shared database. Lihat
`../PANDUAN-INTEGRASI.md` untuk cara setup lengkap bersama
`bsi-survey-kiosk`.

## Quick start

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve --port=8001
```

Lalu daftar akun admin pertama lewat `/register`.
