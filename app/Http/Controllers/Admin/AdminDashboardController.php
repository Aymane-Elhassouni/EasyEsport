<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminDashboardService;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct(
        protected AdminDashboardService $adminDashboardService
    ) {}

    /**
     * Affiche le dashboard principal de l'administration.
     */
    public function index()
    {
        $stats = $this->adminDashboardService->getGlobalStats();
        $activity = $this->adminDashboardService->getRecentActivity();

        return view('pages.admin.dashboard', array_merge($stats, $activity));
    }

    public function superAdminDashboard()
    {
        $stats = $this->adminDashboardService->getGlobalStats();
        $activity = $this->adminDashboardService->getRecentActivity();

        return view('pages.super_admin.dashboard', array_merge($stats, $activity));
    }

    public function ocrMonitor()
    {
        return view('pages.admin.ocr');
    }
}
