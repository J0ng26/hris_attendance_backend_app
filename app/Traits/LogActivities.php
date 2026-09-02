<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait LogActivities
{
    public static function bootLogActivities(): void
    {
        static::created(fn($model) => $model->logActivity('created'));
        static::updated(fn($model) => $model->logActivity('updated'));
        static::deleted(fn($model) => $model->logActivity('deleted'));
    }

    protected function logActivity(string $action): void
    {
        DB::transaction(function () use ($action) {
            $subjectType = $this::class;

            ActivityLog::create([
                'subject_type' => $subjectType,
                'action' => $action,
                'user_id' => Auth::id(),
                'changes' => $this->formatChanges($action),
            ]);
        });
    }

    protected function formatChanges(string $action): array
    {
        $original = $this->filterTimestampColumns($this->getOriginal());
        $current = $this->filterTimestampColumns($this->getAttributes());
        $changes = $this->filterTimestampColumns($this->getChanges());

        return match ($action) {
            'created' => [
                'before' => null,
                'after' => $current
            ],
            'updated' => [
                'before' => $this->withPrimaryKey(array_intersect_key($original, $changes), $original),
                'after' => $this->withPrimaryKey($changes, $current)
            ],
            'deleted' => [
                'before' => $original,
                'after' => null
            ],
            default => [
                'before' => null,
                'after' => null
            ],
        };
    }

    protected function filterTimestampColumns(array $attributes): array
    {
        return Arr::except($attributes, [
            $this->getCreatedAtColumn(),
            $this->getUpdatedAtColumn(),
        ]);
    }

    protected function withPrimaryKey(array $changes, array $attributes): array
    {
        $keyName = $this->getKeyName();
        $keyValue = $attributes[$keyName] ?? $this->getKey();

        return array_merge([$keyName => $keyValue], $changes);
    }
}
