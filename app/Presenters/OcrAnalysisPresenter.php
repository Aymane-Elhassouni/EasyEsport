<?php

namespace App\Presenters;

use App\DTOs\OcrAnalysisDTO;

class OcrAnalysisPresenter
{
    public function __construct(
        protected OcrAnalysisDTO $analysis
    ) {}

    public function getConfidenceClass(): string
    {
        if ($this->analysis->confidence >= 80) return 'text-success';
        if ($this->analysis->confidence >= 50) return 'text-warning';
        return 'text-danger';
    }

    public function getFormattedConfidence(): string
    {
        return number_format($this->analysis->confidence, 1) . '%';
    }

    public function __get($name)
    {
        $camelName = \Illuminate\Support\Str::camel($name);
        
        if (property_exists($this->analysis, $camelName)) {
            return $this->analysis->$camelName;
        }

        return $this->analysis->$name;
    }
}
