<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;

use App\Http\Controllers\Partner\DashboardController as PartnerDashboardController;
use App\Http\Controllers\Partner\EventController as PartnerEventController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PartnerRegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES (Dapat diakses pengunjung tanpa login)
|--------------------------------------------------------------------------
*/

// Home & Detail Event
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/welcome', [HomeController::class, 'index'])->name('welcome'); // Alias untuk penanganan redirect
Route::get('/event/{event}', [EventController::class, 'show'])->name('events.show');

// Route Auth User (Login & Register Manual)
Route::get('/loginUser', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/loginUser', [LoginController::class, 'login'])->name('login.post');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route Auth Google
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Pendaftaran Partner Publik (Mendukung Guest & Auth User)
Route::get('/partner/register', [PartnerRegisterController::class, 'create'])->name('partner.register');
Route::post('/partner/register', [PartnerRegisterController::class, 'store'])->name('partner.register.store');

// Halaman Informasi Status Partner (Pending / Wait for Approval)
Route::get('/partner/pending', function () {
    return view('partner.pending');
})->name('partner.pending');

// Webhook Midtrans (Callback)
Route::match(['GET', 'POST'], '/midtrans/callback', [MidtransWebhookController::class, 'handle']);


/*
|--------------------------------------------------------------------------
| 2. USER TERPROTEKSI (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Tiket Saya
    Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');

    // Alur Checkout & Pembayaran
    Route::get('/checkout/{event}', [CheckoutController::class, 'create'])->name('checkout.create');
    Route::post('/checkout/{event}', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/payment/{order_id}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::get('/success/{order_id}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Kirim Review
    Route::post('/event/{event}/review', [ReviewController::class, 'store'])->name('review.store');
});


/*
|--------------------------------------------------------------------------
| 3. PARTNER / HIMA AREA (Khusus Organisasi/Penyelenggara Event)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'partner'])->prefix('partner')->name('partner.')->group(function () {
    // Dashboard Khusus Partner
    Route::get('/dashboard', [PartnerDashboardController::class, 'index'])->name('dashboard');

    // Event Management Khusus Partner
    Route::resource('events', PartnerEventController::class);
});


/*
|--------------------------------------------------------------------------
| 4. ADMIN & SUPERADMIN AREA
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // Autentikasi Admin Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth'])->group(function () {

        // Global Dashboard & Transaksi Seluruh Platform
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/transactions', [AdminTransactionController::class, 'index'])->name('transactions.index');

        // Master Data Kategori Event
        Route::get('/category', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/category', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/category/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // Pengawasan Seluruh Event
        Route::resource('events', EventAdminController::class);

        // Fitur EKSKLUSIF (Hanya KHUSUS SUPERADMIN)
        Route::middleware(['superadmin'])->group(function () {
            // Action Approve & Reject Partner
            Route::patch('/partners/{partner}/approve', [PartnerController::class, 'approve'])->name('partners.approve');
            Route::patch('/partners/{partner}/reject', [PartnerController::class, 'reject'])->name('partners.reject');

            // Management Master Data Partner
            Route::resource('partners', PartnerController::class);
        });

    });

});