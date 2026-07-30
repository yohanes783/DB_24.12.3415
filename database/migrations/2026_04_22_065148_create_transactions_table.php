<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Relasi ke User Pembeli (Nullable jika mengizinkan guest checkout)
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // Relasi ke Partner/Penyelenggara (Kunci Analitik Pendapatan Tenant)
            $table->foreignId('partner_id')->nullable()->constrained()->onDelete('cascade');
            
            // Relasi ke Event
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();

            $table->string('order_id')->unique(); // ID Pesanan unik
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->integer('total_price');
            $table->string('status')->default('pending'); // pending, success, failed, expired
            $table->string('snap_token')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
