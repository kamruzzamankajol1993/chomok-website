<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Runs for installations where the earlier ENUM migration was already applied.
        // Keeps dine_in, delivery, takeaway, pickup and any future order type intact.
        Schema::table('orders', function (Blueprint $table): void {
            $table->string('order_type', 50)->default('dine_in')->change();
        });
    }

    public function down(): void
    {
        // A conversion back to ENUM could lose data, so no destructive rollback.
    }
};
