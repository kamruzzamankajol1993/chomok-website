<?php

use App\Models\AboutPageContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('about_page_contents', function (Blueprint $table): void {
            $table->string('story_heading_prefix')->nullable();
            $table->string('story_heading_suffix')->nullable();
            $table->string('story_heading_image')->nullable();
            $table->string('story_left_image')->nullable();
            $table->string('story_index', 20)->nullable();
            $table->string('story_button_text', 100)->nullable();
            $table->string('story_button_link', 500)->nullable();
            $table->text('story_description')->nullable();
            $table->string('story_avatar_1', 5)->nullable();
            $table->string('story_avatar_2', 5)->nullable();
            $table->string('story_avatar_3', 5)->nullable();
            $table->string('story_trust_label')->nullable();
            $table->string('story_right_image')->nullable();

            $table->string('mission_label', 100)->nullable();
            $table->text('mission_text')->nullable();
            $table->string('mission_main_image')->nullable();
            $table->string('mission_secondary_image_1')->nullable();
            $table->string('mission_secondary_image_2')->nullable();
            $table->string('vision_label', 100)->nullable();
            $table->text('vision_text')->nullable();

            $table->string('services_heading_line_1')->nullable();
            $table->string('services_heading_line_2')->nullable();
            $table->text('services_description')->nullable();
            $table->string('services_button_text', 100)->nullable();
            $table->string('services_button_link', 500)->nullable();
            $table->string('service_1_title')->nullable();
            $table->text('service_1_text')->nullable();
            $table->string('service_2_title')->nullable();
            $table->text('service_2_text')->nullable();
            $table->string('service_3_title')->nullable();
            $table->text('service_3_text')->nullable();
            $table->string('service_4_title')->nullable();
            $table->text('service_4_text')->nullable();

            $table->string('reviews_eyebrow', 100)->nullable();
            $table->string('reviews_heading_line_1')->nullable();
            $table->string('reviews_heading_line_2')->nullable();
            $table->text('reviews_subtext')->nullable();
            $table->string('reviews_summary')->nullable();
            $table->unsignedTinyInteger('review_1_rating')->default(5);
            $table->text('review_1_quote')->nullable();
            $table->string('review_1_initial', 5)->nullable();
            $table->string('review_1_name')->nullable();
            $table->string('review_1_role')->nullable();
            $table->unsignedTinyInteger('review_2_rating')->default(5);
            $table->text('review_2_quote')->nullable();
            $table->string('review_2_initial', 5)->nullable();
            $table->string('review_2_name')->nullable();
            $table->string('review_2_role')->nullable();
            $table->unsignedTinyInteger('review_3_rating')->default(5);
            $table->text('review_3_quote')->nullable();
            $table->string('review_3_initial', 5)->nullable();
            $table->string('review_3_name')->nullable();
            $table->string('review_3_role')->nullable();
        });

        if (Schema::hasTable('about_page_contents')) {
            DB::table('about_page_contents')->update(AboutPageContent::websiteDefaults());
        }
    }

    public function down(): void
    {
        Schema::table('about_page_contents', function (Blueprint $table): void {
            $table->dropColumn([
                'story_heading_prefix', 'story_heading_suffix', 'story_heading_image', 'story_left_image',
                'story_index', 'story_button_text', 'story_button_link', 'story_description',
                'story_avatar_1', 'story_avatar_2', 'story_avatar_3', 'story_trust_label', 'story_right_image',
                'mission_label', 'mission_text', 'mission_main_image', 'mission_secondary_image_1', 'mission_secondary_image_2',
                'vision_label', 'vision_text',
                'services_heading_line_1', 'services_heading_line_2', 'services_description', 'services_button_text', 'services_button_link',
                'service_1_title', 'service_1_text', 'service_2_title', 'service_2_text',
                'service_3_title', 'service_3_text', 'service_4_title', 'service_4_text',
                'reviews_eyebrow', 'reviews_heading_line_1', 'reviews_heading_line_2', 'reviews_subtext', 'reviews_summary',
                'review_1_rating', 'review_1_quote', 'review_1_initial', 'review_1_name', 'review_1_role',
                'review_2_rating', 'review_2_quote', 'review_2_initial', 'review_2_name', 'review_2_role',
                'review_3_rating', 'review_3_quote', 'review_3_initial', 'review_3_name', 'review_3_role',
            ]);
        });
    }
};
