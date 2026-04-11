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
        Schema::create('ocr_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->unique()->constrained()->onDelete('cascade');
            $table->string('screenshot_path');
            $table->float('confidence');
            $table->enum('status', ['pending', 'processed', 'failed'])->default('pending');
            $table->timestamp('analyzed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ocr_analyses');
    }
};
