<?php

namespace App\Modules\AppSettings\Repository;

use App\Modules\EloquentRepositoryInterface;

interface AppSettingsRepositoryInterface extends EloquentRepositoryInterface
{
    public function lockApi();
    public function unlockApi();
    public function apiInfo();
}
