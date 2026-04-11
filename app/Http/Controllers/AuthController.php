<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:8|confirmed',
        ], [
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $user = $this->authService->register($data);

        // Auto-login after registration
        $token = \PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth::fromUser($user);

        return redirect()->route('dashboard')->withCookie(cookie('token', $token, 60, null, null, false, true));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $token = $this->authService->login($credentials);

        if (!$token) {
            return back()->withErrors(['email' => 'L\'adresse e-mail ou le mot de passe est incorrect.']);
        }

        return redirect()->route('dashboard')->withCookie(cookie('token', $token, 60, null, null, false, true));
    }

    public function logout()
    {
        Auth::guard('api')->logout();

        return redirect()->route('login')->withoutCookie('token');
    }
}
