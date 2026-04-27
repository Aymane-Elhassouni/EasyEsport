<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct(
        protected AuthServiceInterface $authService
    ) {}

    public function showRegister()
    {
        return view('auth.register');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function register(RegisterRequest $request)
    {
        $user  = $this->authService->register($request->validated());
        $token = JWTAuth::fromUser($user);

        // Connexion au guard web pour Blade (@auth)
        Auth::guard('web')->login($user);

        return $this->handleRedirectWithCookie($user, $token);
    }

    public function login(LoginRequest $request)
    {
        $token = $this->authService->login($request->validated());

        if (!$token) {
            return back()->withErrors([
                'email' => 'L\'adresse e-mail ou le mot de passe est incorrect.',
            ]);
        }

        // Récupérer l'utilisateur via le token JWT
        $user = JWTAuth::setToken($token)->authenticate();
        
        // Ponter vers la session web pour les vues
        Auth::guard('web')->login($user);
        \Illuminate\Support\Facades\Cache::put('user_online_' . $user->id, true, now()->addMinutes(5));

        return $this->handleRedirectWithCookie($user, $token);
    }

    /**
     * Centralisation de la logique de redirection par rôle.
     */
    protected function handleRedirectWithCookie($user, $token)
    {
        $user->loadMissing('role');

        if ($user->hasRole('super_admin')) {
            $url = route('admin.system.dashboard');
        } elseif ($user->hasRole('admin')) {
            $url = route('admin.dashboard');
        } else {
            $url = route('player.dashboard');
        }

        return redirect($url)
            ->withCookie($this->authService->makeTokenCookie($token));
    }

    public function logout(Request $request)
    {
        try { $this->authService->logout(); } catch (\Throwable) {}

        Auth::guard('web')->logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('login')
            ->withCookie(cookie()->forget('token'));
    }
}