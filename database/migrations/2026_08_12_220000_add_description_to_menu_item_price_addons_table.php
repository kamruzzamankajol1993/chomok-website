<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('menu_item_price_addons') && ! Schema::hasColumn('menu_item_price_addons', 'description')) {
            Schema::table('menu_item_price_addons', function (Blueprint $table): void {
                $table->text('description')->nullable()->after('name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('menu_item_price_addons') && Schema::hasColumn('menu_item_price_addons', 'description')) {
            Schema::table('menu_item_price_addons', function (Blueprint $table): void {
                $table->dropColumn('description');
            });
        }
    }
};
