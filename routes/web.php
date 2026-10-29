<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Opd\DashboardController as OpdDashboardController;
use App\Http\Controllers\Warga\ReportController as WargaReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/lacak', [HomeController::class, 'track'])->name('track');
Route::get('/laporan', [HomeController::class, 'publicReports'])->name('reports.public');
Route::get('/laporan/detail/{ticket_number}', [HomeController::class, 'showPublicReport'])->name('reports.public.show');
Route::get('/opd', [HomeController::class, 'opds'])->name('opds.public');

// Fitur AI Chatbot
Route::get('/tanya-ai', [AiChatController::class, 'index'])->name('ai.chat.page');
Route::post('/api/ai/chat', [AiChatController::class, 'chat'])->name('ai.chat.send');

/*
|--------------------------------------------------------------------------
| Authentication & Registration Routes (Warga vs OPD)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Step 1: Pilihan Warga atau Perangkat Daerah
    Route::get('/register', [AuthController::class, 'showRegisterChoice'])->name('register');

    // Step 2: Registrasi Warga
    Route::get('/register/warga', [AuthController::class, 'showRegisterWarga'])->name('register.warga');
    Route::post('/register/warga', [AuthController::class, 'storeRegisterWarga']);

    // Step 2: Registrasi Perangkat Daerah (OPD)
    Route::get('/register/opd', [AuthController::class, 'showRegisterOpd'])->name('register.opd');
    Route::post('/register/opd', [AuthController::class, 'storeRegisterOpd']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Portal Warga Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:warga'])->prefix('warga')->name('warga.')->group(function () {
    Route::get('/dashboard', [WargaReportController::class, 'dashboard'])->name('dashboard');
    Route::get('/laporan', [WargaReportController::class, 'index'])->name('reports.index');
    Route::get('/laporan/buat', [WargaReportController::class, 'create'])->name('reports.create');
    Route::post('/laporan', [WargaReportController::class, 'store'])->name('reports.store');
    Route::get('/laporan/{id}', [WargaReportController::class, 'show'])->name('reports.show');
});

/*
|--------------------------------------------------------------------------
| Portal Admin Pemkab Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/laporan', [AdminDashboardController::class, 'reports'])->name('reports.index');
    Route::get('/laporan/{id}', [AdminDashboardController::class, 'showReport'])->name('reports.show');
    Route::post('/laporan/{id}/disposisi', [AdminDashboardController::class, 'disposisi'])->name('reports.disposisi');
    Route::post('/laporan/{id}/tolak', [AdminDashboardController::class, 'reject'])->name('reports.reject');
    Route::get('/opd', [AdminDashboardController::class, 'opds'])->name('opds.index');
    Route::get('/pengguna', [AdminDashboardController::class, 'users'])->name('users.index');
});

/*
|--------------------------------------------------------------------------
| Portal Perangkat Daerah (OPD) Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:opd'])->prefix('opd')->name('opd.')->group(function () {
    Route::get('/dashboard', [OpdDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/laporan', [OpdDashboardController::class, 'reports'])->name('reports.index');
    Route::get('/laporan/{id}', [OpdDashboardController::class, 'showReport'])->name('reports.show');
    Route::post('/laporan/{id}/proses', [OpdDashboardController::class, 'startProcess'])->name('reports.process');
    Route::post('/laporan/{id}/selesai', [OpdDashboardController::class, 'resolve'])->name('reports.resolve');
});
