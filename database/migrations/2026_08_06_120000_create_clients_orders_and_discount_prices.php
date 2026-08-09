<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_item_prices', function (Blueprint $table): void {
            $table->decimal('discount_price', 12, 2)->nullable()->after('price');
        });

        Schema::create('clients', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone', 50);
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('can_login')->default(false)->index();
            $table->string('password')->nullable();
            $table->enum('status', ['active', 'blocked'])->default('active')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['branch_id', 'status']);
        });

        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('order_number')->unique();
            $table->enum('source', ['admin', 'website'])->default('admin')->index();
            $table->enum('order_type', ['dine_in', 'delivery'])->default('dine_in')->index();
            $table->enum('status', ['pending', 'confirmed', 'processing', 'delivered', 'cancelled'])->default('pending')->index();
            $table->enum('payment_type', ['cash', 'cash_on_delivery', 'mfs', 'bank', 'split'])->default('cash');
            $table->string('payment_reference')->nullable();
            $table->decimal('split_cash', 12, 2)->default(0);
            $table->decimal('split_mfs', 12, 2)->default(0);
            $table->decimal('split_bank', 12, 2)->default(0);
            $table->string('split_mfs_reference')->nullable();
            $table->string('split_bank_reference')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone', 50);
            $table->string('customer_email')->nullable();
            $table->text('customer_address')->nullable();
            $table->text('delivery_address')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('service_charge_rate', 8, 2)->default(0);
            $table->decimal('service_charge_amount', 12, 2)->default(0);
            $table->string('tax_label')->default('VAT');
            $table->decimal('tax_rate', 8, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('delivery_charge', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('due_amount', 12, 2)->default(0);
            $table->text('note')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('notification_seen_at')->nullable();
            $table->timestamp('notification_dismissed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['branch_id', 'created_at']);
            $table->index(['source', 'notification_seen_at', 'notification_dismissed_at'], 'orders_notification_index');
        });

        Schema::create('order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('menu_item_price_id')->nullable()->constrained()->nullOnDelete();
            $table->string('item_name');
            $table->string('size_label')->nullable();
            $table->decimal('unit_price', 12, 2);
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('addon_total', 12, 2)->default(0);
            $table->decimal('line_total', 12, 2);
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('order_item_addons', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('addon_id')->nullable()->constrained()->nullOnDelete();
            $table->string('addon_name');
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('line_total', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item_addons');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('clients');

        Schema::table('menu_item_prices', function (Blueprint $table): void {
            $table->dropColumn('discount_price');
        });
    }
};
