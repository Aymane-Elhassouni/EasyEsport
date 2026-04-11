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
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreignId('team_id')->nullable()->after('creator_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('tournament_id')->nullable()->after('team_id')->constrained('tournaments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');
            $table->dropForeign(['tournament_id']);
            $table->dropColumn('tournament_id');
        });
    }
};
