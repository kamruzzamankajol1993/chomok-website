<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homepage_contents', function (Blueprint $table): void {
            $table->id();
            $table->string('about_badge_text')->nullable();
            $table->string('about_heading_line_1')->nullable();
            $table->string('about_heading_line_2')->nullable();
            $table->string('about_pill_image')->nullable();
            $table->string('about_circle_image')->nullable();
            $table->text('about_paragraph_text')->nullable();
            $table->string('about_button_text')->nullable();
            $table->string('about_trust_badge')->nullable();
            $table->timestamps();
        });

        Schema::create('home_slides', function (Blueprint $table): void {
            $table->id();
            $table->string('image')->nullable();
            $table->string('eyebrow_text')->nullable();
            $table->string('title_line_1');
            $table->string('title_line_2')->nullable();
            $table->text('subtext')->nullable();
            $table->string('button_1_text')->nullable();
            $table->string('button_1_link')->nullable();
            $table->string('button_2_text')->nullable();
            $table->string('button_2_link')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('home_promo_cards', function (Blueprint $table): void {
            $table->id();
            $table->string('image')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('price')->nullable();
            $table->string('button_text')->nullable();
            $table->string('color_theme')->default('dark');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('about_page_contents', function (Blueprint $table): void {
            $table->id();
            $table->string('hero_title_line_1')->nullable();
            $table->string('hero_title_line_2')->nullable();
            $table->string('hero_button_text')->nullable();
            $table->string('hero_button_link')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('seat_heading')->nullable();
            $table->string('seat_list_item_1')->nullable();
            $table->string('seat_list_item_2')->nullable();
            $table->string('seat_list_item_3')->nullable();
            $table->string('seat_button_text')->nullable();
            $table->string('seat_est_year')->nullable();
            $table->string('seat_image')->nullable();
            $table->string('support_heading')->nullable();
            $table->text('diet_band_text')->nullable();
            $table->string('video_heading')->nullable();
            $table->string('video_link')->nullable();
            $table->string('video_image')->nullable();
            $table->string('work_badge_text')->nullable();
            $table->string('work_heading')->nullable();
            $table->string('step_1_icon')->nullable();
            $table->string('step_1_title')->nullable();
            $table->string('step_2_icon')->nullable();
            $table->string('step_2_title')->nullable();
            $table->string('step_3_icon')->nullable();
            $table->string('step_3_title')->nullable();
            $table->string('step_4_icon')->nullable();
            $table->string('step_4_title')->nullable();
            $table->text('testimonial_quote')->nullable();
            $table->string('testimonial_author')->nullable();
            $table->string('cta_heading')->nullable();
            $table->string('cta_button_text')->nullable();
            $table->timestamps();
        });

        Schema::create('about_feature_cards', function (Blueprint $table): void {
            $table->id();
            $table->string('image')->nullable();
            $table->string('title');
            $table->string('meta_tag_1')->nullable();
            $table->string('meta_tag_2')->nullable();
            $table->string('meta_tag_3')->nullable();
            $table->string('button_style')->default('dark');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('contact_page_contents', function (Blueprint $table): void {
            $table->id();
            $table->string('hero_eyebrow_text')->nullable();
            $table->string('hero_title')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('address_icon')->nullable();
            $table->text('address')->nullable();
            $table->string('phone_icon')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email_icon')->nullable();
            $table->string('email_address')->nullable();
            $table->string('hours_icon')->nullable();
            $table->string('opening_hours')->nullable();
            $table->string('form_heading')->nullable();
            $table->string('submit_button_text')->nullable();
            $table->boolean('notify_admin_by_email')->default(false);
            $table->text('map_address')->nullable();
            $table->text('map_embed_url')->nullable();
            $table->timestamps();
        });

        Schema::create('contact_queries', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('subject');
            $table->text('message');
            $table->enum('status', ['new', 'pending', 'closed'])->default('new')->index();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        $now = now();
        DB::table('homepage_contents')->insert([
            'about_badge_text' => 'Taste Of World Class Food', 'about_heading_line_1' => 'Experience',
            'about_heading_line_2' => 'Culinary Excellence',
            'about_paragraph_text' => 'A Good Restaurant Is Like A Vacation, It Transports You, And It Becomes A Lot More Than Just About The Food',
            'about_button_text' => 'Our Delicious Item', 'about_trust_badge' => '10K+ Trusted by Families',
            'created_at' => $now, 'updated_at' => $now,
        ]);

        DB::table('home_slides')->insert([
            ['eyebrow_text' => 'Fresh From The Oven', 'title_line_1' => 'Authentic Taste,', 'title_line_2' => 'Delivered Fresh', 'subtext' => 'Hand-tossed pizzas made with fresh basil, ripe tomatoes and melted cheese, every single time.', 'button_1_text' => 'View Menu', 'button_1_link' => 'index.php#menu', 'button_2_text' => 'Book a Table', 'button_2_link' => 'book.php', 'sort_order' => 1, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['eyebrow_text' => 'Big, Bold & Juicy', 'title_line_1' => 'Juicy Burgers,', 'title_line_2' => 'Bold Flavors', 'subtext' => 'Thick, juicy patties stacked high with fresh veggies and melted cheese in a toasted bun.', 'button_1_text' => 'View Menu', 'button_1_link' => 'index.php#menu', 'button_2_text' => 'Book a Table', 'button_2_link' => 'book.php', 'sort_order' => 2, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
            ['eyebrow_text' => 'Golden & Crispy', 'title_line_1' => 'Crispy Tenders,', 'title_line_2' => 'Made To Share', 'subtext' => 'Golden fried chicken tenders served with crispy fries and your favorite dipping sauces.', 'button_1_text' => 'View Menu', 'button_1_link' => 'index.php#menu', 'button_2_text' => 'Book a Table', 'button_2_link' => 'book.php', 'sort_order' => 3, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);

        foreach ([['Fried Rice', '$11.50'], ['Pizza', '$12.50'], ['Burger', '$11.50'], ['Pasta', '$11.50'], ['Fried Chicken', '$14.50']] as $index => [$title, $price]) {
            DB::table('home_promo_cards')->insert(['title' => $title, 'description' => 'Delicious food packed with flavor and freshness.', 'price' => $price, 'button_text' => 'Buy Now', 'color_theme' => 'dark', 'sort_order' => $index + 1, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now]);
        }

        DB::table('about_page_contents')->insert([
            'hero_title_line_1' => 'Welcome To Chomok,', 'hero_title_line_2' => 'Where Every Bite Tells A Story!', 'hero_button_text' => 'View Menu', 'hero_button_link' => 'index.php#menu',
            'seat_heading' => 'A Home Kitchen Now Has A Seat At The Table.', 'seat_list_item_1' => 'Fresh ingredients, prepared to order every single time', 'seat_list_item_2' => 'Recipes rooted in real, home-style cooking', 'seat_list_item_3' => 'Fast delivery across Chattogram & Rajshahi', 'seat_button_text' => 'View Menu', 'seat_est_year' => '2017',
            'support_heading' => "We're Always Here For You", 'diet_band_text' => 'From savory 🍕 pizzas to juicy 🍔 burgers and crispy 🍗 fried chicken, our menu offers a variety of options to suit all diets, preferences, and occasions.',
            'video_heading' => '- Taste The Chomok Difference -', 'work_badge_text' => 'Ordering Made Easy', 'work_heading' => 'How Does It Work',
            'step_1_icon' => '📋', 'step_1_title' => 'Browse Our Menu', 'step_2_icon' => '🍽️', 'step_2_title' => 'Pick Your Favorite', 'step_3_icon' => '🛒', 'step_3_title' => 'Place Your Order', 'step_4_icon' => '❤️', 'step_4_title' => 'Enjoy Your Meal',
            'testimonial_quote' => "Chomok has been our family's go-to for pizza night since they opened their first outlet. You can genuinely taste the home-style care in every bite.", 'testimonial_author' => '— A Happy Chomok Regular', 'cta_heading' => "Don't Wait — Order Now!", 'cta_button_text' => 'Order Now',
            'created_at' => $now, 'updated_at' => $now,
        ]);

        foreach ([['Breakfast Cravings', 'Light meal', 'Fruit drink', 'From 7am'], ['Lunch Rush', 'Spicy meal', 'Beverage', 'From 1pm'], ['Dinner Feast', 'Family meal', 'Beverage', 'From 7pm']] as $index => $card) {
            DB::table('about_feature_cards')->insert(['title' => $card[0], 'meta_tag_1' => $card[1], 'meta_tag_2' => $card[2], 'meta_tag_3' => $card[3], 'button_style' => 'dark', 'sort_order' => $index + 1, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now]);
        }

        DB::table('contact_page_contents')->insert([
            'hero_eyebrow_text' => "We'd Love To Hear From You", 'hero_title' => 'Contact Us',
            'address_icon' => '📍', 'address' => '394 Brothers Mansion, East Rampur, Halishahar, Chittagong.',
            'phone_icon' => '📞', 'phone_number' => '+880 XXX-XXXXXX', 'email_icon' => '✉️', 'email_address' => 'hello@chomok.com',
            'hours_icon' => '🕐', 'opening_hours' => 'Monday to Saturday, 10am – 7pm', 'form_heading' => 'Send Us A Message', 'submit_button_text' => 'Send Message',
            'notify_admin_by_email' => false, 'map_address' => '394 Brothers Mansion, East Rampur, Halishahar, Chittagong, Bangladesh', 'map_embed_url' => 'https://www.google.com/maps?q=394%20Brothers%20Mansion&output=embed',
            'created_at' => $now, 'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_queries');
        Schema::dropIfExists('contact_page_contents');
        Schema::dropIfExists('about_feature_cards');
        Schema::dropIfExists('about_page_contents');
        Schema::dropIfExists('home_promo_cards');
        Schema::dropIfExists('home_slides');
        Schema::dropIfExists('homepage_contents');
    }
};
