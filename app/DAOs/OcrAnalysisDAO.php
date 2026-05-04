<?php

namespace App\DAOs;

use App\Models\OcrAnalysis;

class OcrAnalysisDAO
{
    public function create(array $data): OcrAnalysis
    {
        return OcrAnalysis::create($data);
    }

    public function getLatestForUser(int $userId): ?OcrAnalysis
    {
        return OcrAnalysis::where('user_id', $userId)
            ->latest()
            ->first();
    }
}
