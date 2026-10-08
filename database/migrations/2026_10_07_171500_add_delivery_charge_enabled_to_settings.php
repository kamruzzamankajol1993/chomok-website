<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('settings', 'delivery_charge_enabled')) {
            Schema::table('settings', function (Blueprint $table): void {
                $table->boolean('delivery_charge_enabled')->default(true)->after('service_charge');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('settings', 'delivery_charge_enabled')) {
            Schema::table('settings', function (Blueprint $table): void {
                $table->dropColumn('delivery_charge_enabled');
            });
        }
    }
};
