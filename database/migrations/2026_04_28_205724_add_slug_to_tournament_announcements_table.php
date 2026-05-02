<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\TournamentAnnouncement;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tournament_announcements', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('title');
        });

        TournamentAnnouncement::all()->each(fn($a) => $a->update([
            'slug' => Str::slug($a->title) . '-' . $a->id
        ]));
    }

    public function down(): void
    {
        Schema::table('tournament_announcements', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
