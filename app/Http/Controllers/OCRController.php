<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\OCRService;
use App\Presenters\OcrAnalysisPresenter;

class OCRController extends Controller
{
    public function __construct(
        protected OCRService $ocrService
    ) {}

    public function scan(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        try {
            $result = $this->ocrService->scan($request->file('image'));
            
            if ($result['success']) {
                $presenter = new OcrAnalysisPresenter($result['data']);
                
                return response()->json([
                    'success' => true,
                    'data' => [
                        'playerName' => $presenter->playerName,
                        'rank' => $presenter->rank,
                        'stats' => $presenter->stats,
                        'confidence' => $presenter->getFormattedConfidence(),
                        'confidenceClass' => $presenter->getConfidenceClass(),
                    ]
                ]);
            }

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error("OCR Scan Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to process image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'playerName' => 'nullable|string',
            'rank' => 'required|string',
            'stats' => 'required|string',
            'confidence' => 'nullable'
        ]);

        try {
            $user = auth()->user();
            
            // 1. Update general profile stats
            $user->profile()->increment('total_matches');

            // 2. Update rank in the first game profile found
            $gameProfile = $user->playerGameProfiles()->first();
            if ($gameProfile) {
                $gameProfile->update([
                    'rank' => $validated['rank'],
                    'game_stats' => array_merge($gameProfile->game_stats ?? [], ['last_ocr_scan' => now()->toDateTimeString()])
                ]);
            }

            // 3. Persist analysis using DAO via Service or directly
            $this->ocrService->ocrAnalysisDao->create([
                'user_id' => auth()->id(),
                'player_name' => $validated['playerName'] ?? 'Unknown',
                'rank' => $validated['rank'],
                'stats' => $validated['stats'],
                'confidence' => (float) str_replace('%', '', $validated['confidence'] ?? 0),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save data: ' . $e->getMessage()
            ], 500);
        }
    }
}
