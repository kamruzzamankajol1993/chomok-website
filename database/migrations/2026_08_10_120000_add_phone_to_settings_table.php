<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('settings') && ! Schema::hasColumn('settings', 'phone')) {
            Schema::table('settings', function (Blueprint $table): void {
                $table->string('phone', 50)->nullable()->after('address');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('settings') && Schema::hasColumn('settings', 'phone')) {
            Schema::table('settings', function (Blueprint $table): void {
                $table->dropColumn('phone');
            });
        }
    }
};
