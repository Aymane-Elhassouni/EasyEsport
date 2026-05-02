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
        Schema::create('group_team_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('tournament_groups')->onDelete('cascade');
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->integer('played')->default(0);
            $table->integer('wins')->default(0);
            $table->integer('draws')->default(0);
            $table->integer('losses')->default(0);
            $table->integer('points')->default(0);
            $table->integer('score_diff')->default(0); // For tie-breaking
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_team_stats');
    }
};
