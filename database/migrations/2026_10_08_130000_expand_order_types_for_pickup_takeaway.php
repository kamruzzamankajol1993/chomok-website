<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Convert the existing ENUM to a string without altering any order values.
        Schema::table('orders', function (Blueprint $table): void {
            $table->string('order_type', 50)->default('dine_in')->change();
        });
    }

    public function down(): void
    {
        // Intentionally retain the string type to avoid truncating newer order types.
    }
};
