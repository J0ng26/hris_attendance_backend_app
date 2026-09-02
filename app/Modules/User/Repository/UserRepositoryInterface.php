<?php

namespace App\Modules\User\Repository;

use App\Models\User;
use App\Modules\EloquentRepositoryInterface;
use Illuminate\Support\Collection;

interface UserRepositoryInterface extends EloquentRepositoryInterface
{
    public function findByUsername(string $username, array $relations = []): ?User;
    public function findByEmail(string $email, array $relations = []): ?User;
    public function resetPassword(string $username, string $password);
}
