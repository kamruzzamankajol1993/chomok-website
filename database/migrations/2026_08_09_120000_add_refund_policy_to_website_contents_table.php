<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('website_contents', 'refund_policy')) {
            Schema::table('website_contents', function (Blueprint $table): void {
                $table->longText('refund_policy')->nullable()->after('privacy_policy');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('website_contents', 'refund_policy')) {
            Schema::table('website_contents', function (Blueprint $table): void {
                $table->dropColumn('refund_policy');
            });
        }
    }
};
