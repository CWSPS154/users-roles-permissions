<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('identifier')->unique();
            $table->json('panel_ids')->nullable();
            $table->string('route')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
        Schema::table('permissions', function (Blueprint $table) {
            $table->foreignUuid('parent_id')->nullable()->after('route')->constrained('permissions')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
