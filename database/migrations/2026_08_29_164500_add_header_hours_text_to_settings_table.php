<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('settings', 'header_hours_text')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->string('header_hours_text', 255)
                    ->default('Opening & Closing time (Saturday to Thursday) : 12PM to 10:30PM (Friday 2PM to 10:30PM)')
                    ->after('closing_time');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('settings', 'header_hours_text')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn('header_hours_text');
            });
        }
    }
};
