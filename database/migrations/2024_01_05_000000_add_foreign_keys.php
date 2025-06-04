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
        // Add foreign keys to fellowships table
        Schema::table('fellowships', function (Blueprint $table) {
            $table->foreign('leader_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('pastor_id')->references('id')->on('users')->onDelete('set null');
        });

        // Add foreign key to users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('fellowship_id')->references('id')->on('fellowships')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['fellowship_id']);
        });

        Schema::table('fellowships', function (Blueprint $table) {
            $table->dropForeign(['leader_id']);
            $table->dropForeign(['pastor_id']);
        });
    }
}; 