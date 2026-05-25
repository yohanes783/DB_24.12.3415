<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // <-- 1. Pastikan baris ini ada
use App\Models\Category;             // <-- 2. Pastikan baris ini ada

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 3. Bagikan variabel $categories otomatis ke SELURUH file blade layout
        View::share('categories', Category::all());
    }
}
