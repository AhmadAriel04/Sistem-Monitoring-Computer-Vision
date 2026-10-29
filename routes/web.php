<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Sistem Monitor Survey - Routes
|--------------------------------------------------------------------------
| Semua rute di sini butuh login. Sistem ini membaca data survey yang
| ditulis oleh Sistem Survey (Kiosk) lewat shared database, dan mengelola
| master data (kategori, pegawai, pengaturan).
|
| Hak akses per role:
| - admin   : akses penuh (lihat + tambah/edit/hapus + kelola user)
| - bosm    : hanya bisa melihat (dashboard, kategori, pegawai, laporan, qrcode)
| - manager : hanya bisa melihat (dashboard, kategori, pegawai, laporan, qrcode)
*/
Route::redirect('/', '/login');

Route::middleware(['auth'])->group(function () {

    // Bisa diakses oleh Admin, BOSM, dan Manager (view-only)
    Route::middleware(['role:admin,bosm,manager'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/monitor', [DashboardController::class, 'monitor'])
            ->name('monitor');

        Route::get('/categories', [CategoryController::class, 'index'])
            ->name('categories.index');

        Route::get('/employees', [EmployeeController::class, 'index'])
            ->name('employees.index');

        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');

        Route::get('/reports/pdf', [ReportController::class, 'pdf'])
            ->name('reports.pdf');

        Route::get('/reports/excel', [ReportController::class, 'excel'])
            ->name('reports.excel');

        // Menampilkan QR code yang mengarah ke Sistem Survey (Kiosk),
        // alamatnya diatur lewat KIOSK_URL di .env
        Route::get('/qrcode', function () {
            return view('qrcode', [
                'kioskUrl' => config('services.kiosk.url'),
            ]);
        })->name('qrcode');
    });

    // Khusus Admin: kelola data master (tambah/edit/hapus) & kelola user
    Route::middleware(['role:admin'])->group(function () {

        Route::resource('categories', CategoryController::class)->except(['index', 'show']);
        Route::resource('employees', EmployeeController::class)->except(['index', 'show']);

        Route::resource('users', UserController::class);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
