<?php

namespace App\Modules;

use App\Modules\EloquentRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BaseRepository implements EloquentRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection
     */
    public function index(array $relations = [], string $sort = 'created_at', string $order = 'desc'): Collection
    {
        $query = $this->model->with($relations)->orderBy($sort, $order);

        return $query->get();
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes, array $relations = []): Model
    {
        $model = $this->model->create($attributes);
        if (!empty($relations)) {
            $model = $model->load($relations);
        }
        return $model;
    }

    /**
     * @param array $attributes
     * @param string|int $id
     * @return Model
     */
    public function update(string|int $id, array $attributes, array $relations = []): Model
    {
        $model = $this->model->find($id);
        $model->update($attributes);
        if (!empty($relations)) {
            $model = $model->load($relations);
        }
        return $model;
    }

    /**
     * @param string|int $id
     * @param array $relations
     * @return Model
     */
    public function findByPrimaryId(string|int $id, array $relations = []): ?Model
    {
        $query = $this->model->with($relations);

        return $query->find($id);
    }

    /**
     * @param string|int $id
     *
     * @return Boolean
     */
    public function delete(string|int $id): bool
    {
        return $this->model->find($id)->delete();
    }

    public function getDistinct(array $column_with_values, string $select)
    {
        $finalQuery = $this->model->query();
        for ($x = 0; $x < count($column_with_values); $x++) {
            if (property_exists($column_with_values[$x], 'operator')) {
                if ($column_with_values[$x]->operator === 'equals') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === '%not like%') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, 'NOT LIKE', '%' . $column_with_values[$x]->value . '%');
                } elseif ($column_with_values[$x]->operator === '%like%') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, 'LIKE', '%' . $column_with_values[$x]->value . '%');
                } elseif ($column_with_values[$x]->operator === 'not equals') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '!=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'equals date') {
                    $finalQuery = $finalQuery->whereDate($column_with_values[$x]->column, $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'is null') {
                    $finalQuery = $finalQuery->whereNull($column_with_values[$x]->column);
                } elseif ($column_with_values[$x]->operator === 'is not null') {
                    $finalQuery = $finalQuery->whereNotNull($column_with_values[$x]->column);
                }
            } else {
                $finalQuery = $finalQuery->where($column_with_values[$x]->column, $column_with_values[$x]->value);
            }
        }

        $finalQuery = $finalQuery->select($select);
        $finalQuery = $finalQuery->distinct();
        $finalQuery = $finalQuery->get();

        return $finalQuery;
    }

    /**
     * @param array $column_with_values
     * @param array $relations
     * @param string $order_by
     * @param string $order_by_column
     * @return Model
     */
    public function findOneBySpecificColumn(array $column_with_values, array $relations, array $order_by): ?Model
    {
        $finalQuery = $this->model->query();

        for ($x = 0; $x < count($column_with_values); $x++) {
            if (property_exists($column_with_values[$x], 'operator')) {
                if ($column_with_values[$x]->operator === 'equals') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === '%not like%') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, 'NOT LIKE', '%' . $column_with_values[$x]->value . '%');
                } elseif ($column_with_values[$x]->operator === '%like%') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, 'LIKE', '%' . $column_with_values[$x]->value . '%');
                } elseif ($column_with_values[$x]->operator === 'not equals') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '!=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'equals date') {
                    $finalQuery = $finalQuery->whereDate($column_with_values[$x]->column, $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'higher or equals date') {
                    $finalQuery = $finalQuery->whereDate($column_with_values[$x]->column, '>=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'lower or equals date') {
                    $finalQuery = $finalQuery->whereDate($column_with_values[$x]->column, '<=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'higher than date') {
                    $finalQuery = $finalQuery->whereDate($column_with_values[$x]->column, '>', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'lower than date') {
                    $finalQuery = $finalQuery->whereDate($column_with_values[$x]->column, '<', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'is null') {
                    $finalQuery = $finalQuery->whereNull($column_with_values[$x]->column);
                } elseif ($column_with_values[$x]->operator === 'is not null') {
                    $finalQuery = $finalQuery->whereNotNull($column_with_values[$x]->column);
                } elseif ($column_with_values[$x]->operator === 'less than or equal') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '<=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'greater than or equal') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '>=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'less than') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '<', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'greater than') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '>', $column_with_values[$x]->value);
                }
            } else {
                $finalQuery = $finalQuery->where($column_with_values[$x]->column, $column_with_values[$x]->value);
            }
        }

        $finalQuery = $finalQuery->with($relations);

        for ($x = 0; $x < count($order_by); $x++) {
            $finalQuery = $finalQuery->orderBy($order_by[$x]->column, $order_by[$x]->operator);
        }

        $finalQuery = $finalQuery->first();

        return $finalQuery;
    }

    /**
     * @param array $column_with_values
     * @param array $relations
     * @param string $order_by
     * @param string $order_by_column
     * @return Model
     */
    public function findManyBySpecificColumn(array $column_with_values, array $relations, array $order_by): ?Collection
    {
        $finalQuery = $this->model->query();

        for ($x = 0; $x < count($column_with_values); $x++) {
            if (property_exists($column_with_values[$x], 'operator')) {
                if ($column_with_values[$x]->operator === 'equals') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === '%not like%') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, 'NOT LIKE', '%' . $column_with_values[$x]->value . '%');
                } elseif ($column_with_values[$x]->operator === '%like%') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, 'LIKE', '%' . $column_with_values[$x]->value . '%');
                } elseif ($column_with_values[$x]->operator === 'not equals') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '!=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'equals date') {
                    $finalQuery = $finalQuery->whereDate($column_with_values[$x]->column, $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'higher or equals date') {
                    $finalQuery = $finalQuery->whereDate($column_with_values[$x]->column, '>=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'lower or equals date') {
                    $finalQuery = $finalQuery->whereDate($column_with_values[$x]->column, '<=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'higher than date') {
                    $finalQuery = $finalQuery->whereDate($column_with_values[$x]->column, '>', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'lower than date') {
                    $finalQuery = $finalQuery->whereDate($column_with_values[$x]->column, '<', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'is null') {
                    $finalQuery = $finalQuery->whereNull($column_with_values[$x]->column);
                } elseif ($column_with_values[$x]->operator === 'is not null') {
                    $finalQuery = $finalQuery->whereNotNull($column_with_values[$x]->column);
                } elseif ($column_with_values[$x]->operator === 'less than or equal') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '<=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'greater than or equal') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '>=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'less than') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '<', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'greater than') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '>', $column_with_values[$x]->value);
                }
            } else {
                $finalQuery = $finalQuery->where($column_with_values[$x]->column, $column_with_values[$x]->value);
            }
        }

        $finalQuery = $finalQuery->with($relations);

        for ($x = 0; $x < count($order_by); $x++) {
            $finalQuery = $finalQuery->orderBy($order_by[$x]->column, $order_by[$x]->operator);
        }

        $finalQuery = $finalQuery->get();

        return $finalQuery;
    }

    /**
     * @param array $column_with_values
     * @param array $relations
     * @param string $order_by
     * @param string $order_by_column
     * @return Model
     */
    public function countBySpecificColumn(array $column_with_values, array $relations, array $order_by): int
    {
        $finalQuery = $this->model->query();

        for ($x = 0; $x < count($column_with_values); $x++) {
            if (property_exists($column_with_values[$x], 'operator')) {
                if ($column_with_values[$x]->operator === 'equals') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === '%not like%') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, 'NOT LIKE', '%' . $column_with_values[$x]->value . '%');
                } elseif ($column_with_values[$x]->operator === '%like%') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, 'LIKE', '%' . $column_with_values[$x]->value . '%');
                } elseif ($column_with_values[$x]->operator === 'not equals') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '!=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'is null') {
                    $finalQuery = $finalQuery->whereNull($column_with_values[$x]->column);
                } elseif ($column_with_values[$x]->operator === 'is not null') {
                    $finalQuery = $finalQuery->whereNotNull($column_with_values[$x]->column);
                } elseif ($column_with_values[$x]->operator === 'equals date') {
                    $finalQuery = $finalQuery->whereDate($column_with_values[$x]->column, $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'higher or equals date') {
                    $finalQuery = $finalQuery->whereDate($column_with_values[$x]->column, '>=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'lower or equals date') {
                    $finalQuery = $finalQuery->whereDate($column_with_values[$x]->column, '<=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'higher than date') {
                    $finalQuery = $finalQuery->whereDate($column_with_values[$x]->column, '>', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'lower than date') {
                    $finalQuery = $finalQuery->whereDate($column_with_values[$x]->column, '<', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'less than or equal') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '<=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'greater than or equal') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '>=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'less than') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '<', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'greater than') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '>', $column_with_values[$x]->value);
                }
            } else {
                $finalQuery = $finalQuery->where($column_with_values[$x]->column, $column_with_values[$x]->value);
            }
        }

        $finalQuery = $finalQuery->with($relations);

        for ($x = 0; $x < count($order_by); $x++) {
            $finalQuery = $finalQuery->orderBy($order_by[$x]->column, $order_by[$x]->operator);
        }

        $finalQuery = $finalQuery->count();

        return $finalQuery;
    }

    /**
     * @param array $column_with_values
     * @param array $relations
     * @param string $order_by
     * @param string $order_by_column
     * @return Model
     */
    public function findManyBySpecificColumnLastTwenty(array $column_with_values, array $relations, array $order_by): ?Collection
    {
        $finalQuery = $this->model->query();

        for ($x = 0; $x < count($column_with_values); $x++) {
            if (property_exists($column_with_values[$x], 'operator')) {
                if ($column_with_values[$x]->operator === 'equals') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === '%not like%') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, 'NOT LIKE', '%' . $column_with_values[$x]->value . '%');
                } elseif ($column_with_values[$x]->operator === '%like%') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, 'LIKE', '%' . $column_with_values[$x]->value . '%');
                } elseif ($column_with_values[$x]->operator === 'not equals') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '!=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'is null') {
                    $finalQuery = $finalQuery->whereNull($column_with_values[$x]->column);
                } elseif ($column_with_values[$x]->operator === 'is not null') {
                    $finalQuery = $finalQuery->whereNotNull($column_with_values[$x]->column);
                } elseif ($column_with_values[$x]->operator === 'equals date') {
                    $finalQuery = $finalQuery->whereDate($column_with_values[$x]->column, $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'higher or equals date') {
                    $finalQuery = $finalQuery->whereDate($column_with_values[$x]->column, '>=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'lower or equals date') {
                    $finalQuery = $finalQuery->whereDate($column_with_values[$x]->column, '<=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'higher than date') {
                    $finalQuery = $finalQuery->whereDate($column_with_values[$x]->column, '>', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'lower than date') {
                    $finalQuery = $finalQuery->whereDate($column_with_values[$x]->column, '<', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'less than or equal') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '<=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'greater than or equal') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '>=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'less than') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '<', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'greater than') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '>', $column_with_values[$x]->value);
                }
            } else {
                $finalQuery = $finalQuery->where($column_with_values[$x]->column, $column_with_values[$x]->value);
            }
        }

        $finalQuery = $finalQuery->with($relations);

        for ($x = 0; $x < count($order_by); $x++) {
            $finalQuery = $finalQuery->orderBy($order_by[$x]->column, $order_by[$x]->operator);
        }

        $finalQuery = $finalQuery->take(20);
        $finalQuery = $finalQuery->get();

        return $finalQuery;
    }

    /**
     * @param array $conditions
     * @return Boolean
     */
    public function deleteByCondition(array $conditions): bool
    {
        return DB::transaction(function () use ($conditions) {
            $finalQuery = $this->model->query();

            foreach ($conditions as $condition) {
                $finalQuery = $finalQuery->where($condition->column, $condition->value);
            }

            $finalQuery = $finalQuery->delete();

            return $finalQuery;
        });
    }

    /**
     * @param array $column_with_values
     * @param array $relations
     * @param string $order_by
     * @param string $order_by_column
     * @return Model
     */
    public function countManyBySpecificColumn(array $column_with_values, array $relations, array $order_by): ?int
    {
        $finalQuery = $this->model->query();

        for ($x = 0; $x < count($column_with_values); $x++) {
            $finalQuery = $finalQuery->where($column_with_values[$x]->column, $column_with_values[$x]->value);
        }

        $finalQuery = $finalQuery->with($relations);

        for ($x = 0; $x < count($order_by); $x++) {
            $finalQuery = $finalQuery->orderBy($order_by[$x]->column, $order_by[$x]->operator);
        }

        $finalQuery = $finalQuery->count();

        return $finalQuery;
    }

    /**
     * @param array $column_with_values
     * @param array $relations
     * @param string $order_by
     * @param string $order_by_column
     * @return Model
     */
    public function editManyBySpecificColumn(array $column_with_values, array $columns_for_update): ?int
    {
        $finalQuery = $this->model->query();

        for ($x = 0; $x < count($column_with_values); $x++) {
            if (property_exists($column_with_values[$x], 'operator')) {
                if ($column_with_values[$x]->operator === 'equals') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === '%not like%') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, 'NOT LIKE', '%' . $column_with_values[$x]->value . '%');
                } elseif ($column_with_values[$x]->operator === '%like%') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, 'LIKE', '%' . $column_with_values[$x]->value . '%');
                } elseif ($column_with_values[$x]->operator === 'not equals') {
                    $finalQuery = $finalQuery->where($column_with_values[$x]->column, '!=', $column_with_values[$x]->value);
                } elseif ($column_with_values[$x]->operator === 'is null') {
                    $finalQuery = $finalQuery->whereNull($column_with_values[$x]->column);
                } elseif ($column_with_values[$x]->operator === 'is not null') {
                    $finalQuery = $finalQuery->whereNotNull($column_with_values[$x]->column);
                }
            } else {
                $finalQuery = $finalQuery->where($column_with_values[$x]->column, $column_with_values[$x]->value);
            }
        }

        $finalQuery = $finalQuery->update($columns_for_update);

        return $finalQuery;
    }

    /**
     * @param array $data
     *
     * @return Model
     */
    public function insertMany(array $data): bool
    {
        return $this->model->insert($data);
    }
}
