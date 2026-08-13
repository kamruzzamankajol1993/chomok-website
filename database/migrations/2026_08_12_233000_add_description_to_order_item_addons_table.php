<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('order_item_addons') && ! Schema::hasColumn('order_item_addons', 'description')) {
            Schema::table('order_item_addons', function (Blueprint $table): void {
                $table->text('description')->nullable()->after('addon_name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('order_item_addons') && Schema::hasColumn('order_item_addons', 'description')) {
            Schema::table('order_item_addons', function (Blueprint $table): void {
                $table->dropColumn('description');
            });
        }
    }
};
