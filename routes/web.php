<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MidtransWebhookController;
use Illuminate\Support\Facades\Route;

// Pengalihan otomatis ke halaman login admin
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// ==========================================
// ROUTE ADMIN AREA (Digabung menjadi satu)
// ==========================================
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Rute autentikasi (Bebas akses / Tanpa middleware)
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Seluruh rute admin yang wajib login
    // Catatan: Jika memicu error lagi, ganti 'admin' dengan nama middleware kustom Anda yang benar
    Route::middleware(['auth', 'admin'])->group(function () {
        
        // Dashboard & Transaksi
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
        
        // Data Partner
        Route::get('partners', [PartnerController::class, 'index'])->name('partners.index');
        Route::get('partners/create', [PartnerController::class, 'create'])->name('partners.create');
        Route::post('partners', [PartnerController::class, 'store'])->name('partners.store');
        Route::get('partners/{id}/edit', [PartnerController::class, 'edit'])->name('partners.edit');
        Route::put('partners/{id}', [PartnerController::class, 'update'])->name('partners.update');
        Route::delete('partners/{id}', [PartnerController::class, 'destroy'])->name('partners.destroy');

        // Data Kategori
        Route::get('category', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('category', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('category/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('category/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Data Event (Menggunakan EventAdminController milik Admin)
        Route::resource('events', EventAdminController::class);
    });
});

// ==========================================
// ROUTE USER AREA
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/event/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');

// Proses Checkout & Pembayaran
Route::get('/checkout', [EventController::class, 'checkout'])->name('checkout');
Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');

// Webhook Midtrans
Route::post('/midtrans/callback', [MidtransWebhookController::class, 'handle']);
