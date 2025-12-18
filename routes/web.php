<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BantuanController;
use App\Http\Controllers\PenerimaController;

// Public routes
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('authenticate');

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Bantuan Resources
    Route::resource('bantuan', BantuanController::class)->names([
        'index' => 'bantuan.index',
        'create' => 'bantuan.create',
        'store' => 'bantuan.store',
        'show' => 'bantuan.show',
        'edit' => 'bantuan.edit',
        'update' => 'bantuan.update',
        'destroy' => 'bantuan.destroy'
    ]);

    // Bantuan-Penerima relationship routes
    Route::post('/bantuan/{bantuan}/penerima/{penerima}/attach', [BantuanController::class, 'attachPenerima'])
        ->name('bantuan.attachPenerima');
        
    Route::delete('/bantuan/{bantuan}/penerima/{penerima}/detach', [BantuanController::class, 'detachPenerima'])
        ->name('bantuan.detachPenerima');

    Route::get('/bantuan/{bantuan}/add-penerimas', [BantuanController::class, 'addPenerimas'])
        ->name('bantuan.addPenerimas');
        
    Route::post('/bantuan/{bantuan}/store-penerimas', [BantuanController::class, 'storePenerimas'])
        ->name('bantuan.storePenerimas');


    // Penerima Resources
    Route::resource('penerima', PenerimaController::class)->names([
        'index' => 'penerima.index',
        'create' => 'penerima.create',
        'store' => 'penerima.store',
        'show' => 'penerima.show',
        'edit' => 'penerima.edit',
        'update' => 'penerima.update',
        'destroy' => 'penerima.destroy'
    ]);

      

    // Penerima-Bantuan relationship routes
    Route::post('/penerima/{penerima}/bantuan/{bantuan}/attach', [PenerimaController::class, 'attachBantuan'])
        ->name('penerima.attachBantuan');
        
    Route::delete('/penerima/{penerima}/bantuan/{bantuan}/detach', [PenerimaController::class, 'detachBantuan'])
        ->name('penerima.detachBantuan');

    Route::get('/penerima/{penerima}/add-bantuans', [PenerimaController::class, 'addBantuans'])
        ->name('penerima.addBantuans');
});
