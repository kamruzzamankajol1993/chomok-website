<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('branches', 'map_iframe')) {
            Schema::table('branches', function (Blueprint $table) {
                $table->text('map_iframe')->nullable()->after('google_map_link');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('branches', 'map_iframe')) {
            Schema::table('branches', function (Blueprint $table) {
                $table->dropColumn('map_iframe');
            });
        }
    }
};
