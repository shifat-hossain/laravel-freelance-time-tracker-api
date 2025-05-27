<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\TimeLog\PdfTimeLogController;
use App\Http\Controllers\Api\TimeLog\TimeLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register', [RegisterController::class, 'index']);
Route::post('/login', [LoginController::class, 'authenticated']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/logout', [LoginController::class, 'logout']);
    Route::prefix('profile')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/update', 'update');
    });

    Route::apiResources([
        'clients' => ClientController::class,
        'projects' => ProjectController::class,
        'time-logs' => TimeLogController::class,
    ], [
        'only' => ['index', 'edit', 'store', 'update', 'destroy']
    ]);

    Route::get('/pdf-time-logs', PdfTimeLogController::class);

    Route::prefix('reports')->controller(ReportController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/project', 'per_project_wise_report');
        Route::get('/day', 'per_day_wise_report');
        Route::get('/client', 'client_wise_report');
    });
});
