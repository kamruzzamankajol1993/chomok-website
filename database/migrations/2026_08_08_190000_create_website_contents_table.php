<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('website_contents')) {
            Schema::create('website_contents', function (Blueprint $table): void {
                $table->id();
                $table->longText('terms_and_conditions')->nullable();
                $table->longText('privacy_policy')->nullable();
                $table->longText('delivery_info')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('website_contents') && ! DB::table('website_contents')->exists()) {
            DB::table('website_contents')->insert([
                'terms_and_conditions' => null,
                'privacy_policy' => null,
                'delivery_info' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('website_contents');
    }
};
