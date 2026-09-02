<?php

namespace App\Modules;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Interface EloquentRepositoryInterface
 * @package App\Repositories
 */
interface EloquentRepositoryInterface
{
    public function index(array $relations = [],string $sort = 'created_at', string $order = 'desc'): Collection;
    public function findByPrimaryId(string|int $id, array $relations = []): ?Model;
    public function create(array $attributes, array $relations = []): Model;
    public function update(string|int $id, array $attributes, array $relations = []): Model;
    public function delete(string|int $id): bool;
    public function getDistinct(array $column_with_values, string $select);
    public function findOneBySpecificColumn(array $column_with_values, array $relations, array $order_by): ?Model;
    public function findManyBySpecificColumn(array $column_with_values, array $relations, array $order_by): ?Collection;
    public function countBySpecificColumn(array $column_with_values, array $relations, array $order_by): int;
    public function findManyBySpecificColumnLastTwenty(array $column_with_values, array $relations, array $order_by): ?Collection;
    public function countManyBySpecificColumn(array $column_with_values, array $relations, array $order_by): ?int;
    public function editManyBySpecificColumn(array $column_with_values, array $columns_for_update): ?int;
    public function insertMany(array $data): bool;
    public function deleteByCondition(array $conditions): bool;
}
