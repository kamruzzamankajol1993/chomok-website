<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('home_promo_cards', function (Blueprint $table): void {
            $table->unsignedTinyInteger('banner_slot')->nullable()->after('id');
            $table->string('link', 1000)->nullable()->after('image');
            $table->unique('banner_slot', 'home_promo_cards_banner_slot_unique');
        });

        Schema::table('homepage_contents', function (Blueprint $table): void {
            $table->string('hungry_left_image')->nullable()->after('about_trust_badge');
            $table->string('hungry_right_image')->nullable()->after('hungry_left_image');
            $table->string('hungry_line_one')->nullable()->after('hungry_right_image');
            $table->string('hungry_line_two')->nullable()->after('hungry_line_one');
            $table->text('hungry_subtext')->nullable()->after('hungry_line_two');
            $table->string('hungry_button_text')->nullable()->after('hungry_subtext');
        });

        $existing = DB::table('home_promo_cards')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->limit(5)
            ->get();

        foreach ($existing as $index => $banner) {
            DB::table('home_promo_cards')->where('id', $banner->id)->update([
                'banner_slot' => $index + 1,
                'sort_order' => $index + 1,
                'updated_at' => now(),
            ]);
        }

        for ($slot = $existing->count() + 1; $slot <= 5; $slot++) {
            DB::table('home_promo_cards')->insert([
                'banner_slot' => $slot,
                'image' => null,
                'link' => null,
                'title' => 'Lower Banner Slot '.$slot,
                'description' => null,
                'price' => null,
                'button_text' => null,
                'color_theme' => 'dark',
                'sort_order' => $slot,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('home_promo_cards', function (Blueprint $table): void {
            $table->dropUnique('home_promo_cards_banner_slot_unique');
            $table->dropColumn(['banner_slot', 'link']);
        });

        Schema::table('homepage_contents', function (Blueprint $table): void {
            $table->dropColumn([
                'hungry_left_image', 'hungry_right_image', 'hungry_line_one',
                'hungry_line_two', 'hungry_subtext', 'hungry_button_text',
            ]);
        });
    }
};
