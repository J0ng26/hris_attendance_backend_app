<?php


namespace App\Modules\UserType\Repository;

use App\Modules\EloquentRepositoryInterface;
use Illuminate\Support\Collection;

interface UserTypeRepositoryInterface extends EloquentRepositoryInterface
{
    public function all(string $departmentId): Collection;
    public function allExceptHigherPosition(int $userLevel): Collection;
    public function getUserTypeWithPermissions(string $id);
}
