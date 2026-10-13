<?php // Dikerjakan secara mandiri
// Dikerjakan secara mandiri

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InformasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect('/admin/dashboard')
            : redirect('/informasi');
    }

    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Akun & Profil (Semua Pengguna Terotentikasi)
Route::middleware('auth')->group(function () {
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/informasi', [SiswaController::class, 'index'])->name('informasi.index');
    Route::get('/informasi/{id}', [SiswaController::class, 'show'])->name('informasi.show');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('informasi', InformasiController::class);
});
