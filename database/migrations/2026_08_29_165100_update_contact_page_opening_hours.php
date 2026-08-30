<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('contact_page_contents') && Schema::hasColumn('contact_page_contents', 'opening_hours')) {
            DB::table('contact_page_contents')->update([
                'opening_hours' => 'Saturday to Thursday: 12PM to 10:30PM (Friday 2PM to 10:30PM)',
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('contact_page_contents') && Schema::hasColumn('contact_page_contents', 'opening_hours')) {
            DB::table('contact_page_contents')->update([
                'opening_hours' => 'Monday to Saturday, 10am – 7pm',
                'updated_at' => now(),
            ]);
        }
    }
};
