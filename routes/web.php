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
    SettingsController
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
    Route::view('/teams', 'pages.teams.manage')->name('teams');
});

Route::middleware(['jwt.bridge', 'guest', 'role.redirect'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/register', 'showRegister')->name('register');
        Route::post('/register', 'register');
        Route::get('/login', 'showLogin')->name('login');
        Route::post('/login', 'login');
    });
});

Route::middleware(['jwt.bridge', 'auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['role:super_admin'])->prefix('admin/system')->name('admin.system.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'superAdminDashboard'])->name('dashboard');
        Route::get('/logs', [AdminDashboardController::class, 'logs'])->name('logs');
        Route::get('/audit-ocr', [AdminDashboardController::class, 'ocrAudit'])->name('ocr.audit');
        Route::view('/roles', 'admin.roles')->name('roles');
    });

    Route::middleware(['role:super_admin,admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminDashboardController::class, 'users'])->name('users');
        Route::get('/disputes', [AdminDashboardController::class, 'disputes'])->name('disputes');
        Route::get('/ocr-monitor', [AdminDashboardController::class, 'ocrMonitor'])->name('ocr');
        Route::get('/profile', [ProfileController::class, 'showAdmin'])->name('profile');
        
        Route::post('/tournaments/{tournament}/validate', [TournamentController::class, 'validateApp'])->name('applications.validate');
        Route::patch('/disputes/{match}/settle', [MatchController::class, 'settleDispute'])->name('disputes.settle');
    });

    Route::middleware(['role:player,captain'])->prefix('player')->name('player.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'showPlayer'])->name('profile');
    });

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'show')->name('profile.show');
        Route::patch('/profile/update', 'update')->name('profile.update');
        Route::patch('/settings/account', 'updateAccount')->name('settings.account');
        Route::patch('/settings/password', 'updatePassword')->name('settings.password');
    });
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

    Route::controller(TournamentController::class)->group(function () {
        Route::post('/tournaments/{tournament}/register', 'register')->name('tournaments.register');
    });

    Route::prefix('matches')->name('matches.')->group(function () {
        Route::view('/', 'pages.matches')->name('index');
        Route::get('/{match}', fn($id) => view('pages.tournaments.show', compact('id')))->name('show');
        Route::post('/{match}/upload-screenshot', [MatchController::class, 'uploadScreenshot'])->name('upload');
        Route::post('/{match}/dispute', [MatchController::class, 'openDispute'])->name('dispute');
    });

    Route::controller(TeamController::class)->group(function () {
        Route::view('/teams/create', 'pages.create-team')->name('teams.create');
        Route::post('/teams', 'store')->name('teams.store');
        
        Route::prefix('teams/{team}')->name('teams.')->group(function () {
            Route::get('/manage', 'manage')->name('manage')->middleware('role:captain');
            Route::patch('/update', 'update')->name('update')->middleware('role:captain');
            Route::delete('/kick/{user}', 'kickMember')->name('kick')->middleware('role:captain');
            Route::post('/leave', 'leave')->name('leave');
        });
        
        Route::patch('/invitations/{invitation}/handle', 'handleInvitation')->name('handle-request');
    });

    Route::view('/invitations', 'pages.invitations')->name('invitations');
    Route::view('/notifications', 'pages.notifications')->name('notifications');
    Route::view('/upload', 'pages.upload')->name('upload');
    Route::post('/upload-image', [UploadController::class, 'uploadImage'])->name('upload.image');
});