<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\TournamentServiceInterface;
use App\Services\Interfaces\TeamServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TournamentController extends Controller
{
    public function __construct(
        protected TournamentServiceInterface $tournamentService,
        protected TeamServiceInterface $teamService
    ) {}

    public function index()
    {
        $tournaments = $this->tournamentService->getAllActive();
        return view('pages.tournaments', compact('tournaments'));
    }

    public function show(int $id)
    {
        $tournament = $this->tournamentService->getTournamentDetail($id);
        
        // Find if the current user has a team that can join
        $userTeam = $this->teamService->getCurrentTeamForUser(Auth::id());
        
        return view('pages.tournaments.detail', compact('tournament', 'userTeam'));
    }

    public function register(Request $request, int $id)
    {
        $teamId = $request->input('team_id');
        
        if ($this->tournamentService->registerTeam($id, $teamId)) {
            return back()->with('success', 'Votre équipe a été inscrite avec succès !');
        }

        return back()->with('error', 'Échec de l\'inscription. Votre équipe est déjà inscrite ou n\'est pas éligible.');
    }
}
