<?php

namespace App\Modules\AppSettings\Service;

use App\Modules\AppSettings\Repository\AppSettingsRepositoryInterface;

class AppSettingsService
{
    private $appSettingsRepository;

    public function __construct(AppSettingsRepositoryInterface $appSettingsRepository)
    {
        $this->appSettingsRepository = $appSettingsRepository;
    }

    public function all()
    {
        return $this->appSettingsRepository->index();
    }

    public function getApiStatus(): bool
    {
        $data = $this->appSettingsRepository->apiInfo();

        return $data->lock_api;
    }

    public function lockApi()
    {
        $this->appSettingsRepository->lockApi();

        return Response()->json([
            'message' => 'API locked successfully.',
        ], 200);
    }

    public function unlockApi()
    {
        $this->appSettingsRepository->unlockApi();

        return Response()->json([
            'message' => 'API unlocked successfully.',
        ], 200);
    }
}
