<?php

namespace App\Http\Controllers;

use App\Models\GameMatch;
use App\Services\Interfaces\MatchServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatchController extends Controller
{
    public function __construct(
        protected MatchServiceInterface $matchService
    ) {}

    /**
     * Upload a screenshot for a match (OCR validation).
     */
    public function uploadScreenshot(Request $request, GameMatch $match)
    {
        $this->matchService->uploadScreenshot($match->id, $request->all());
        return back()->with('success', 'Screenshot uploaded for match #' . $match->id);
    }

    /**
     * Open a dispute for a match.
     */
    public function openDispute(Request $request, GameMatch $match)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $this->matchService->openDispute($match->id, Auth::id(), $request->reason);

        return back()->with('warning', 'Dispute opened for match #' . $match->id);
    }

    /**
     * Settle a dispute (Admin only).
     */
    public function settleDispute(Request $request, GameMatch $match)
    {
        $request->validate([
            'winner_id' => 'required|exists:teams,id',
        ]);

        $this->matchService->settleDispute($match->id, $request->winner_id);

        return back()->with('success', 'Dispute settled for match #' . $match->id);
    }
}
