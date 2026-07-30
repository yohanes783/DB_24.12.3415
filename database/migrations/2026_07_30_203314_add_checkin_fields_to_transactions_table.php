<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'is_used')) {
                $table->boolean('is_used')->default(false)->after('status');
            }
            if (!Schema::hasColumn('transactions', 'status_checkin')) {
                $table->string('status_checkin')->default('unused')->after('is_used');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['is_used', 'status_checkin']);
        });
    }
};