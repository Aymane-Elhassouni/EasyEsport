<?php

namespace App\DAOs;

use App\Models\Team;
use Illuminate\Pagination\LengthAwarePaginator;

class TeamDAO
{
    public function getPaginatedTeams(?string $query = null, int $perPage = 12): LengthAwarePaginator
    {
        return Team::withCount('members')
            ->when($query, function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->latest()
            ->paginate($perPage);
    }

    public function findByUserId(int $userId): ?Team
    {
        return Team::with(['members'])
            ->where(function ($q) use ($userId) {
                $q->where('captain_id', $userId)
                  ->orWhereHas('members', fn($q) => $q->where('user_id', $userId));
            })
            ->withCount('members')
            ->latest('id')
            ->first();
    }

    public function create(array $data): Team
    {
        return Team::create($data);
    }

    public function findById(int $id): Team
    {
        return Team::findOrFail($id);
    }

    public function update(int $id, array $data): bool
    {
        return Team::where('id', $id)->update($data);
    }
}
