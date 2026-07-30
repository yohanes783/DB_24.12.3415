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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();

            // Terhubung ke User penanggung jawab (Role: Partner)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('name');                      // Nama Organisasi/HIMA
            $table->string('slug')->unique();            // Slug untuk URL profil
            $table->string('logo_url')->nullable();      // Logo organisasi
            $table->text('description')->nullable();     // Profil singkat

            // Kontak Penanggung Jawab (Opsional tapi berguna untuk verifikasi)
            $table->string('phone')->nullable();

            // Status verifikasi kelayakan oleh Superadmin
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
