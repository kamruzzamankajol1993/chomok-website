<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_item_price_addons', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('menu_item_price_id')->constrained('menu_item_prices')->cascadeOnDelete();
            $table->string('name');
            $table->decimal('price', 12, 2)->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('order_item_addons', function (Blueprint $table): void {
            $table->foreignId('menu_item_price_addon_id')
                ->nullable()
                ->after('addon_id')
                ->constrained('menu_item_price_addons')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('order_item_addons', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('menu_item_price_addon_id');
        });

        Schema::dropIfExists('menu_item_price_addons');
    }
};
