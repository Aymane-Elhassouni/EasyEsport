<?php

namespace App\Services;

use App\DAO\Interfaces\UserDAOInterface;
use App\Services\Interfaces\AuthServiceInterface;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthService implements AuthServiceInterface
{
    protected $userDAO;

    public function __construct(UserDAOInterface $userDAO)
    {
        $this->userDAO = $userDAO;
    }

    public function register(array $data): User
    {
        $roleId = Role::where('name', 'player')->first()?->id ?? Role::first()?->id ?? 1;

        return $this->userDAO->save([
            'firstname' => $data['firstname'],
            'lastname'  => $data['lastname'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'role_id'   => $roleId,
        ]);
    }

    public function login(array $credentials): ?string
    {
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return null;
        }

        return $token;
    }
}
