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
        Schema::table('matches', function (Blueprint $table) {
            // Progression linking
            $table->foreignId('next_match_id')->nullable()->constrained('matches')->onDelete('set null');
            $table->enum('position', ['top', 'bottom'])->nullable();
            $table->foreignId('winner_id')->nullable()->constrained('teams')->onDelete('set null');

            // OCR & Validation
            $table->string('team_a_screenshot')->nullable();
            $table->string('team_b_screenshot')->nullable();
            $table->float('ocr_confidence')->default(0);
            
            // Updating status column (Using string for better compatibility during modification)
            $table->string('status')->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropForeign(['next_match_id']);
            $table->dropForeign(['winner_id']);
            $table->dropColumn([
                'next_match_id',
                'position',
                'winner_id',
                'team_a_screenshot',
                'team_b_screenshot',
                'ocr_confidence'
            ]);
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'disputed'])->default('scheduled')->change();
        });
    }
};
