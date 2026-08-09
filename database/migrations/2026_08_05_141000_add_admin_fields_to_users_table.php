<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('image')->nullable()->after('name');
            $table->string('phone', 30)->nullable()->after('image');
            $table->foreignId('branch_id')->nullable()->after('password')->constrained()->nullOnDelete();
            $table->boolean('all_branch_access')->default(false)->after('branch_id');
            $table->string('status')->default('active')->after('all_branch_access')->index();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('branch_id');
            $table->dropColumn(['image', 'phone', 'all_branch_access', 'status']);
        });
    }
};
