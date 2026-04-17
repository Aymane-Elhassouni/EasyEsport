<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tournaments', function (Blueprint $table) {
            $table->dropColumn('registered_at');
        });

        Schema::table('tournament_registrations', function (Blueprint $table) {
            $table->timestamp('registered_at')->nullable()->after('waitlist_position');
        });
    }

    public function down(): void
    {
        Schema::table('tournament_registrations', function (Blueprint $table) {
            $table->dropColumn('registered_at');
        });

        Schema::table('tournaments', function (Blueprint $table) {
            $table->timestamp('registered_at')->nullable();
        });
    }
};
