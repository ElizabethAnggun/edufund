<?php

namespace App\Services;

use App\Contracts\Services\AuthServiceInterface;
use App\Contracts\Services\UserServiceInterface;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        private readonly UserServiceInterface $userService
    ) {}

    public function register(array $data): User
    {
        $user = $this->userService->create($data);
        $user->assignRole($data['role']);
        return $user;
    }

    public function login(array $credentials, bool $remember = false): bool
    {
        return Auth::attempt($credentials, $remember);
    }

    public function logout(): void
    {
        Auth::logout();
    }
}
