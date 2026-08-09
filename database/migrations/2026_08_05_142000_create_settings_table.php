<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('restaurant_name')->default('Chomok');
            $table->string('website_url')->nullable();
            $table->string('admin_panel_url')->nullable();
            $table->string('logo')->nullable();
            $table->string('icon')->nullable();
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->decimal('tax_rate', 8, 2)->default(0);
            $table->string('tax_label')->default('VAT');
            $table->string('tax_registration_number')->nullable();
            $table->decimal('service_charge', 8, 2)->default(0);
            $table->boolean('tax_included')->default(false);
            $table->string('invoice_prefix')->default('INV-');
            $table->unsignedBigInteger('invoice_starting_number')->default(1);
            $table->text('invoice_footer_note')->nullable();
            $table->string('print_paper_size')->default('80mm');
            $table->boolean('show_logo_invoice')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
