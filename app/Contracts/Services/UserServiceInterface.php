<?php

namespace App\Contracts\Services;

use App\Models\User;

interface UserServiceInterface
{
    public function create(array $data): User;
    public function findByEmail(string $email): ?User;
}
