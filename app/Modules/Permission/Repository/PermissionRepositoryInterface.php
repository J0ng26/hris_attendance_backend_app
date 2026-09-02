<?php


namespace App\Modules\Permission\Repository;

use App\Modules\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

interface PermissionRepositoryInterface extends EloquentRepositoryInterface
{
    public function getByPermissionKey(string $key): ?Model;
}
