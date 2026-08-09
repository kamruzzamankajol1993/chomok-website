<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->enum('discount_type', ['fixed', 'percentage'])->default('fixed')->after('subtotal');
            $table->decimal('discount_value', 12, 2)->default(0)->after('discount_type');
        });

        // Preserve the meaning of discounts already stored before this migration.
        DB::table('orders')->update([
            'discount_type' => 'fixed',
            'discount_value' => DB::raw('discount'),
        ]);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropColumn(['discount_type', 'discount_value']);
        });
    }
};
