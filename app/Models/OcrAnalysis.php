<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OcrAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_id',
        'screenshot_path',
        'confidence',
        'status',
        'analyzed_at',
    ];

    protected $casts = [
        'analyzed_at' => 'datetime',
    ];

    public function match()
    {
        return $this->belongsTo(GameMatch::class, 'match_id');
    }
}
