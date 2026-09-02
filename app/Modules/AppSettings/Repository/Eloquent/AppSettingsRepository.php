<?php

namespace App\Modules\AppSettings\Repository\Eloquent;

use App\Models\AppSettings;
use App\Modules\AppSettings\Repository\AppSettingsRepositoryInterface;
use App\Modules\BaseRepository;

class AppSettingsRepository extends BaseRepository implements AppSettingsRepositoryInterface
{
    public function __construct(AppSettings $model)
    {
        parent::__construct($model);
    }

    public function lockApi()
    {
        $data = $this->model->orderBy('id', 'ASC')->first();
        $data->update([
            'lock_api' => true
        ]);

        return $data;
    }

    public function apiInfo()
    {
        $data = $this->model->orderBy('id', 'ASC')->first();

        return $data;
    }

    public function unlockApi()
    {
        $data = $this->model->orderBy('id', 'ASC')->first();
        $data->update([
            'lock_api' => false
        ]);

        return $data;
    }
}
