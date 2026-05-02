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
        Schema::table('games', function (Blueprint $table) {
            $table->enum('type', ['solo', 'squad'])->default('squad')->after('banner');
        });

        Schema::table('tournaments', function (Blueprint $table) {
            $table->integer('players_per_team')->default(1)->after('max_teams');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn('players_per_team');
        });
    }
};
