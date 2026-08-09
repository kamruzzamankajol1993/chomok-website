<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('shop_page_contents')) {
            Schema::create('shop_page_contents', function (Blueprint $table): void {
                $table->id();
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->string('hero_eyebrow_text')->nullable();
                $table->string('hero_title')->nullable();
                $table->string('hero_image')->nullable();
                $table->string('intro_badge_text')->nullable();
                $table->string('intro_heading')->nullable();
                $table->text('intro_text')->nullable();
                $table->string('map_link_text')->nullable();
                $table->timestamps();
            });

            DB::table('shop_page_contents')->insert([
                'meta_title' => 'Shop',
                'meta_description' => 'Find Chomok branches, addresses and Google Maps locations.',
                'hero_eyebrow_text' => 'Dine In & Takeaway',
                'hero_title' => 'Visit Our Shops',
                'intro_badge_text' => 'Find Us',
                'intro_heading' => 'A Chomok Near You',
                'intro_text' => 'From our very first home kitchen in Halishahar to storefronts across Chittagong and now Rajshahi, every Chomok location serves the same fresh, home-style food we started with. Drop by, place a takeaway order, or just come say hello.',
                'map_link_text' => 'View on Google Maps',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (Schema::hasTable('about_page_contents')) {
            Schema::table('about_page_contents', function (Blueprint $table): void {
                if (! Schema::hasColumn('about_page_contents', 'meta_title')) {
                    $table->string('meta_title')->nullable();
                }
                if (! Schema::hasColumn('about_page_contents', 'meta_description')) {
                    $table->text('meta_description')->nullable();
                }
            });
        }

        if (Schema::hasTable('contact_page_contents')) {
            Schema::table('contact_page_contents', function (Blueprint $table): void {
                if (! Schema::hasColumn('contact_page_contents', 'meta_title')) {
                    $table->string('meta_title')->nullable();
                }
                if (! Schema::hasColumn('contact_page_contents', 'meta_description')) {
                    $table->text('meta_description')->nullable();
                }
                if (! Schema::hasColumn('contact_page_contents', 'address_heading')) {
                    $table->string('address_heading')->nullable();
                }
                if (! Schema::hasColumn('contact_page_contents', 'phone_heading')) {
                    $table->string('phone_heading')->nullable();
                }
                if (! Schema::hasColumn('contact_page_contents', 'email_heading')) {
                    $table->string('email_heading')->nullable();
                }
                if (! Schema::hasColumn('contact_page_contents', 'hours_heading')) {
                    $table->string('hours_heading')->nullable();
                }
                if (! Schema::hasColumn('contact_page_contents', 'name_label')) {
                    $table->string('name_label')->nullable();
                }
                if (! Schema::hasColumn('contact_page_contents', 'name_placeholder')) {
                    $table->string('name_placeholder')->nullable();
                }
                if (! Schema::hasColumn('contact_page_contents', 'email_label')) {
                    $table->string('email_label')->nullable();
                }
                if (! Schema::hasColumn('contact_page_contents', 'email_placeholder')) {
                    $table->string('email_placeholder')->nullable();
                }
                if (! Schema::hasColumn('contact_page_contents', 'subject_label')) {
                    $table->string('subject_label')->nullable();
                }
                if (! Schema::hasColumn('contact_page_contents', 'subject_placeholder')) {
                    $table->string('subject_placeholder')->nullable();
                }
                if (! Schema::hasColumn('contact_page_contents', 'message_label')) {
                    $table->string('message_label')->nullable();
                }
                if (! Schema::hasColumn('contact_page_contents', 'message_placeholder')) {
                    $table->string('message_placeholder')->nullable();
                }
            });
        }

        if (Schema::hasTable('about_page_contents')) {
            DB::table('about_page_contents')->whereNull('meta_title')->update([
                'meta_title' => 'About',
                'meta_description' => 'Learn about Chomok, our story, mission, vision, service and customer experience.',
            ]);
        }

        if (Schema::hasTable('contact_page_contents')) {
            DB::table('contact_page_contents')->whereNull('meta_title')->update([
                'meta_title' => 'Contact Us',
                'meta_description' => 'Contact Chomok for location, phone, opening hours and support.',
                'address_heading' => 'Our Address',
                'phone_heading' => 'Call Us',
                'email_heading' => 'Email Us',
                'hours_heading' => 'Opening Hours',
                'name_label' => 'Full Name',
                'name_placeholder' => 'Your name',
                'email_label' => 'Email Address',
                'email_placeholder' => 'you@example.com',
                'subject_label' => 'Subject',
                'subject_placeholder' => 'What is this about?',
                'message_label' => 'Message',
                'message_placeholder' => 'Write your message...',
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_page_contents');

        if (Schema::hasTable('about_page_contents')) {
            Schema::table('about_page_contents', function (Blueprint $table): void {
                $columns = array_values(array_filter(['meta_title', 'meta_description'], fn ($column) => Schema::hasColumn('about_page_contents', $column)));
                if ($columns) {
                    $table->dropColumn($columns);
                }
            });
        }

        if (Schema::hasTable('contact_page_contents')) {
            Schema::table('contact_page_contents', function (Blueprint $table): void {
                $columns = array_values(array_filter([
                    'meta_title', 'meta_description', 'address_heading', 'phone_heading', 'email_heading', 'hours_heading',
                    'name_label', 'name_placeholder', 'email_label', 'email_placeholder', 'subject_label', 'subject_placeholder',
                    'message_label', 'message_placeholder',
                ], fn ($column) => Schema::hasColumn('contact_page_contents', $column)));
                if ($columns) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};
