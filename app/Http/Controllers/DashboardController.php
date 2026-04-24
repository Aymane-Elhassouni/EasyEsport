<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\DashboardServiceInterface;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardServiceInterface $dashboardService
    ) {}

    public function index()
    {
        $user = Auth::user();
        $data = $this->dashboardService->getDashboardData($user->id);
        
        // Pass platform statistics
        $data['total_users'] = \App\Models\User::count();

        return view('dashboard.index', $data);
    }
}
