<?php

namespace App\Http\Controllers;

use App\Models\Invitation;

class InvitationController extends Controller
{
    public function index()
    {
        $invitations = Invitation::with(['team.captain'])
            ->where('invited_user_id', auth()->id())
            ->whereIn('type', ['invitation', 'captain_transfer'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('pages.invitations', compact('invitations'));
    }
}
