<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('subcategories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['category_id', 'is_active']);
        });

        Schema::create('addons', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->decimal('price', 12, 2)->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('menu_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('subcategory_id')->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['category_id', 'subcategory_id']);
        });

        Schema::create('menu_item_images', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
            $table->string('image');
            $table->boolean('is_main')->default(false)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('menu_item_prices', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
            $table->string('size_label')->nullable();
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('addon_menu_item', function (Blueprint $table): void {
            $table->foreignId('addon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->primary(['addon_id', 'menu_item_id']);
        });

        Schema::create('branch_menu_item', function (Blueprint $table): void {
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->primary(['branch_id', 'menu_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_menu_item');
        Schema::dropIfExists('addon_menu_item');
        Schema::dropIfExists('menu_item_prices');
        Schema::dropIfExists('menu_item_images');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('addons');
        Schema::dropIfExists('subcategories');
        Schema::dropIfExists('categories');
    }
};
