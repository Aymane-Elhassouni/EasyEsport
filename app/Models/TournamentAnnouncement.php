<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TournamentAnnouncement extends Model
{
    use HasFactory;

    protected $fillable = ['tournament_id', 'created_by', 'title', 'slug', 'body', 'banner', 'status'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::creating(function ($ann) {
            $base = Str::slug($ann->title);
            $slug = $base;
            $i = 1;
            while (static::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }
            $ann->slug = $slug;
        });
    }

    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner
            ? config('filesystems.disks.s3.url') . '/' . $this->banner
            : null;
    }

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
