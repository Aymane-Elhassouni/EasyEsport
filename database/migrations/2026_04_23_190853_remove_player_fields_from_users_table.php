<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bio', 'handle', 'trophies', 'wins', 'win_rate']);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('bio')->nullable();
            $table->string('handle')->nullable()->unique();
            $table->integer('trophies')->default(0);
            $table->integer('wins')->default(0);
            $table->decimal('win_rate', 5, 2)->default(0);
        });
    }
};
