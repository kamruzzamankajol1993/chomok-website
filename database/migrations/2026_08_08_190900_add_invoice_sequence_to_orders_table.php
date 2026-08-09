<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->unsignedBigInteger('invoice_sequence')->nullable()->after('order_number');
            $table->unique(['branch_id', 'invoice_sequence'], 'orders_branch_invoice_sequence_unique');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropUnique('orders_branch_invoice_sequence_unique');
            $table->dropColumn('invoice_sequence');
        });
    }
};
