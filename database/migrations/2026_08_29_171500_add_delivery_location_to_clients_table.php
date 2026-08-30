<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table): void {
            $table->string('delivery_city')->nullable()->after('address');
            $table->string('delivery_postcode', 50)->nullable()->after('delivery_city');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table): void {
            $table->dropColumn(['delivery_city', 'delivery_postcode']);
        });
    }
};
