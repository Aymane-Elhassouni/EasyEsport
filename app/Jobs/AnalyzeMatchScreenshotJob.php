<?php

namespace App\Jobs;

use App\Models\GameMatch;
use App\Services\MatchValidationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use thiagoalessio\TesseractOCR\TesseractOCR;

class AnalyzeMatchScreenshotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected GameMatch $match,
        protected string $path,
        protected string $teamSide // 'a' or 'b'
    ) {}

    /**
     * Execute the job.
     */
    public function handle(MatchValidationService $validationService, \App\Services\OCRService $ocrService): void
    {
        Log::info("Starting OCR analysis for Match #{$this->match->id}, Team {$this->teamSide}");

        try {
            $file = new \Illuminate\Http\UploadedFile(
                Storage::disk('public')->path($this->path),
                basename($this->path)
            );

            $result = $ocrService->scan($file);
            $text = $result['data']['raw_text'] ?? "";

            Log::info("OCR Extracted Text: " . $text);

            // 3. Regex Matching for scores (e.g., 13-7 or 13 - 7)
            $scoreA = 0;
            $scoreB = 0;
            if (preg_match('/(\d+)\s*-\s*(\d+)/', $text, $matches)) {
                $scoreA = (int)$matches[1];
                $scoreB = (int)$matches[2];
            }

            // 4. Update Match Data
            // Simple logic: if side A uploaded and says 13-7, we trust it for now with confidence 90
            $this->match->update([
                'score_a' => $scoreA,
                'score_b' => $scoreB,
                'ocr_confidence' => 90, // Static for now, in real life we'd calculate this
            ]);

            // 5. Trigger Validation Service
            $validationService->validateMatchResults($this->match);

        } catch (\Exception $e) {
            Log::error("OCR Analysis failed: " . $e->getMessage());
            $this->match->update(['status' => 'dispute']);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Job failed definitely. Moving match #{$this->match->id} to dispute.");
        $this->match->update(['status' => 'dispute']);
    }
}
