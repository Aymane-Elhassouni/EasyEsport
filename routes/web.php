<?php



use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController, 
    DashboardController, 
    ProfileController, 
    UploadController, 
    TournamentController, 
    TeamController, 
    MatchController,
    AnnouncementController,
    SettingsController,
    NotificationController,
    InvitationController
};
use App\Http\Controllers\Admin\AdminDashboardController;

// Public routes — jwt.bridge pour hydrate auth() sans forcer login
Route::middleware(['jwt.bridge'])->group(function () {
    Route::get('/', fn() => view('pages.shared.home'))->middleware('role.redirect')->name('home');
    Route::view('/about', 'pages.shared.about')->name('about');
    Route::view('/faq', 'pages.faq')->name('faq');
    Route::view('/contact', 'pages.contact')->name('contact');
    Route::get('/tournaments', [TournamentController::class, 'index'])->name('tournaments');
    Route::get('/tournaments/{tournament}', [TournamentController::class, 'show'])->name('tournaments.show');
    Route::post('/tournaments/{tournament}/register', [TournamentController::class, 'register'])->name('tournaments.register');
});

Route::middleware(['jwt.bridge', 'guest', 'role.redirect'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/register', 'showRegister')->name('register');
        Route::post('/register', 'register');
        Route::get('/login', 'showLogin')->name('login');
        Route::post('/login', 'login');
    });
});

Route::middleware(['jwt.bridge'])->post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['jwt.bridge', 'auth'])->group(function () {

    Route::middleware(['role:super_admin'])->prefix('admin/system')->name('admin.system.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'superAdminDashboard'])->name('dashboard');
        Route::get('/logs', [AdminDashboardController::class, 'logs'])->name('logs');
        Route::get('/audit-ocr', [AdminDashboardController::class, 'ocrAudit'])->name('ocr.audit');
        Route::view('/roles', 'admin.roles')->name('roles');
    });

    Route::middleware(['role:admin,super_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
        Route::get('/disputes', [AdminDashboardController::class, 'disputes'])->name('disputes');
        Route::get('/ocr-monitor', [AdminDashboardController::class, 'ocrMonitor'])->name('ocr');
        Route::get('/teams', [AdminDashboardController::class, 'teams'])->name('teams');
        Route::delete('/teams/{team}', [AdminDashboardController::class, 'destroyTeam'])->name('teams.destroy');
        Route::get('/profile', [ProfileController::class, 'showAdmin'])->name('profile');
        
        Route::post('/tournaments/{tournament}/validate', [TournamentController::class, 'validateApp'])->name('applications.validate');
        Route::get('/registrations', [TournamentController::class, 'registrations'])->name('registrations');
        Route::get('/tournaments/create', [TournamentController::class, 'create'])->name('tournaments.create');
        Route::post('/tournaments', [TournamentController::class, 'store'])->name('tournaments.store');
        Route::post('/tournaments/{tournament}/launch', [TournamentController::class, 'launch'])->name('tournaments.launch');
        Route::post('/tournaments/{tournament}/knockout', [TournamentController::class, 'generateKnockout'])->name('tournaments.knockout');
        Route::get('/tournaments/{tournament}/teams', [TournamentController::class, 'tournamentTeams'])->name('tournaments.teams');
        Route::patch('/disputes/{match}/settle', [MatchController::class, 'settleDispute'])->name('disputes.settle');
        Route::get('/announcements', [AnnouncementController::class, 'adminIndex'])->name('announcements');
        Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');
        Route::post('/tournaments/{tournament}/announcements', [AnnouncementController::class, 'store'])->name('tournaments.announcements.store');
        Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::get('/disputes', [AdminDashboardController::class, 'disputes'])->name('disputes');
        Route::delete('/announcements/{announcement}/delete', [AnnouncementController::class, 'destroy'])->name('tournaments.announcements.destroy');
    });

    Route::middleware(['role:player,captain'])->prefix('player')->name('player.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'showPlayer'])->name('profile');
        Route::get('/applications', [TournamentController::class, 'myApplications'])->name('applications');
        Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements');
        Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');
    });

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'show')->name('profile.show');
        Route::patch('/profile/update', 'update')->name('profile.update');
        Route::patch('/settings/account', 'updateAccount')->name('settings.account');
        Route::patch('/settings/password', 'updatePassword')->name('settings.password');
    });
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

    Route::prefix('matches')->name('matches.')->group(function () {
        Route::view('/', 'pages.matches')->name('index');
        Route::get('/{match}', fn($id) => view('pages.tournaments.show', compact('id')))->name('show');
        Route::post('/{match}/upload-screenshot', [MatchController::class, 'uploadScreenshot'])->name('upload');
        Route::post('/{match}/dispute', [MatchController::class, 'openDispute'])->name('dispute');
    });

    Route::controller(TeamController::class)->group(function () {
        Route::get('/teams', 'index')->name('teams');
        Route::get('/teams/redirect', 'redirect')->name('teams.redirect');
        Route::get('/teams/create', fn() => view('pages.create-team'))->name('teams.create');
        Route::post('/teams', 'store')->name('teams.store');
        Route::post('/teams/{team}/join-request', 'requestJoin')->name('teams.join-request')->middleware('role:player');
        
        Route::prefix('teams/{team}')->name('teams.')->group(function () {
            Route::post('/invite', 'invite')->name('invite');
            Route::get('/players/search', 'searchPlayers')->name('players.search');
            Route::get('/manage', 'manage')->name('manage');
            Route::patch('/update', 'update')->name('update')->middleware('role:captain');
            Route::delete('/kick/{user}', 'kickMember')->name('kick')->middleware('role:captain');
            Route::post('/transfer-captain', 'transferCaptain')->name('transfer-captain')->middleware('role:captain');
            Route::post('/leave', 'leave')->name('leave');
        });
        
        Route::patch('/invitations/{invitation}/handle', 'handleInvitation')->name('teams.handle-request');
    });

    Route::get('/invitations', [InvitationController::class, 'index'])->name('invitations');
    Route::controller(NotificationController::class)->prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/dropdown', 'dropdown')->name('dropdown');
        Route::get('/', 'index')->name('index');
        Route::delete('/clear', 'clear')->name('clear');
        Route::delete('/{notification}', 'destroy')->name('destroy');
    });
    Route::view('/upload', 'pages.upload')->name('upload');
    Route::post('/scan-ocr', [\App\Http\Controllers\OCRController::class, 'scan'])->name('ocr.scan');
    Route::post('/save-ocr', [\App\Http\Controllers\OCRController::class, 'confirm'])->name('ocr.save');
    Route::post('/upload-image', [UploadController::class, 'uploadImage'])->name('upload.image');
});