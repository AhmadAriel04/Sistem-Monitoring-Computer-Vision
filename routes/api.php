<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\EmployeeApiController;
use App\Http\Controllers\Api\SettingApiController;
use App\Http\Controllers\Api\SurveyAspectApiController;

use App\Http\Controllers\Api\SurveyApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\ReportApiController;
use App\Http\Controllers\Api\RatingApiController;
use App\Http\Controllers\Api\SentimentApiController;
use App\Http\Controllers\Api\CvController;

Route::get('/categories',
    [CategoryApiController::class,'index']);

Route::get('/employees',
    [EmployeeApiController::class,'index']);

Route::get('/employees/category/{id}',
    [EmployeeApiController::class,'byCategory']);

Route::get('/settings',
    [SettingApiController::class,'index']);

Route::get('/survey-aspects',
    [SurveyAspectApiController::class,'index']);

Route::post('/surveys',
    [SurveyApiController::class,'store']);

Route::get('/dashboard',
    [DashboardApiController::class,'index']);

Route::get('/reports',
    [ReportApiController::class,'index']);

Route::get('/ratings',
    [RatingApiController::class,'index']);

Route::get('/sentiments',
    [SentimentApiController::class,'index']);

/*
|--------------------------------------------------------------------------
| COMPUTER VISION API
|--------------------------------------------------------------------------
*/

Route::prefix('cv')
    ->middleware('cv.token')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Health
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/health',
            [CvController::class, 'health']
        );

        /*
        |--------------------------------------------------------------------------
        | Simpan hasil CV
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/sessions',
            [CvController::class, 'storeSession']
        );

        /*
        |--------------------------------------------------------------------------
        | Ambil session yang membutuhkan konfirmasi
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/pending',
            [CvController::class, 'pending']
        );

        /*
        |--------------------------------------------------------------------------
        | Konfirmasi hasil CV
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/sessions/{uid}/confirm',
            [CvController::class, 'confirm']
        );

});