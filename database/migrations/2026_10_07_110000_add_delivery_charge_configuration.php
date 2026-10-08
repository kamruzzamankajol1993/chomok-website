<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table): void {
            $table->decimal('delivery_within_1km_charge', 12, 2)->default(50)->after('service_charge');
            $table->decimal('delivery_outside_base_charge', 12, 2)->default(50)->after('delivery_within_1km_charge');
            $table->decimal('delivery_per_km_charge', 12, 2)->default(10)->after('delivery_outside_base_charge');
        });

        Schema::table('orders', function (Blueprint $table): void {
            $table->string('delivery_charge_option', 30)->nullable()->after('delivery_charge');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropColumn('delivery_charge_option');
        });

        Schema::table('settings', function (Blueprint $table): void {
            $table->dropColumn(['delivery_within_1km_charge', 'delivery_outside_base_charge', 'delivery_per_km_charge']);
        });
    }
};
