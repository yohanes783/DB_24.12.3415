<?php

use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\PartnerController;

// Route User Area
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/event/1', [EventController::class, 'show'])->name('events.show');
Route::get('/checkout', [EventController::class, 'checkout'])->name('checkout');
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');

// Route Admin Area
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

    // --- ROUTE PARTNER (SUDAH DISESUAIKAN) ---
    Route::get('/partners', [PartnerController::class, 'index'])->name('partners.index');
    Route::get('/partners/create', [PartnerController::class, 'create'])->name('partners.create');

    // DIUBAH: Dari '/partners/store' menjadi '/partners' agar klop dengan form action="/admin/partners"
    Route::post('/partners', [PartnerController::class, 'store'])->name('partners.store');

    Route::get('/partners/{id}/edit', [PartnerController::class, 'edit'])->name('partners.edit');
    Route::put('/partners/{id}', [PartnerController::class, 'update'])->name('partners.update');
    Route::delete('/partners/{id}', [PartnerController::class, 'destroy'])->name('partners.destroy');

    // --- ROUTE KATEGORI ---
    Route::get('/category', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/category', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/category/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('events', EventAdminController::class);
});
