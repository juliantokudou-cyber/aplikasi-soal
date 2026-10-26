<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CbtController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Rute Autentikasi Custom
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rute Peserta (Harus Login & Harus Approved)
Route::middleware(['auth', 'verified.cbt'])->group(function () {
    Route::get('/dashboard', [CbtController::class, 'index'])->name('dashboard');
    Route::get('/ujian/mulai', [CbtController::class, 'startExam'])->name('ujian.mulai');

});

// Halaman Tunggu jika user nekat masuk tapi belum di-approve
Route::get('/waiting', function () {
    return view('auth.waiting');
})->name('waiting');

// RUTE KHUSUS ADMIN (Menggunakan struktur paling aman di Laravel 10)
Route::middleware(['auth'])->prefix('admin')->as('admin.')->group(function () {
    
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/approve/{user}', [AdminController::class, 'approve'])->name('approve');
    Route::put('/peserta/{id}', [AdminController::class, 'updatePeserta'])
        ->name('peserta.update');

    Route::delete('/peserta/{id}', [AdminController::class, 'deletePeserta'])
        ->name('peserta.delete');
    
    Route::post('/soal/import', [AdminController::class, 'importSoal'])->name('soal.import');
    Route::post('/soal/reset', [AdminController::class, 'resetSoal'])->name('soal.reset');


});

