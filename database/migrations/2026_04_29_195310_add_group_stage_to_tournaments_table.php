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
        Schema::table('tournaments', function (Blueprint $table) {
            $table->boolean('has_group_stage')->default(false)->after('format');
            $table->integer('teams_per_group')->default(4)->after('has_group_stage');
            $table->integer('qualifiers_per_group')->default(2)->after('teams_per_group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn(['has_group_stage', 'teams_per_group', 'qualifiers_per_group']);
        });
    }
};
