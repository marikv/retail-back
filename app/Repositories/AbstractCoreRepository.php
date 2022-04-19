<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;

abstract  class AbstractCoreRepository
{

    /**
     * @var
     */
    protected mixed $model;

    /**
     *
     */
    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }

    /**
     * @return mixed
     */
    abstract protected function getModelClass(): mixed;


    /**
     * @return \#M#C\App\Repositories\AbstractCoreRepository.getModelClass|\Illuminate\Contracts\Foundation\Application|mixed
     */
    protected function startConditions()
    {
        return clone $this->model;
    }

    /**
     * @param int $id
     * @return mixed
     */
    abstract public function getById(int $id = 0);


    /**
     * @param int $id
     * @return mixed
     */
    public function delete(int $id = 0): mixed
    {
        $model = $this->startConditions()
            ->where('id', '=', $id)
            ->first();
        $model->deleted = true;
        return $model->save();
    }



    /**
     * @param Builder $s
     * @param array|null $pagination
     * @param string $column
     * @param string $direction
     * @return Builder
     */
    protected function standardOrderBy(Builder $s, array $pagination = null, string $column = 'id', string $direction = 'desc') : Builder
    {
        return self::standardOrderByStatic($s, $pagination, $column, $direction);
    }

    /**
     * @param Builder $s
     * @param array|null $pagination
     * @return LengthAwarePaginator
     */
    protected function standardPagination(Builder $s, array $pagination = null) : LengthAwarePaginator
    {
        return self::standardPaginationStatic($s, $pagination);
    }

    /**
     * @param Builder $s
     * @param array|null $pagination
     * @param string $column
     * @param string $direction
     * @return Builder
     */
    protected static function standardOrderByStatic(Builder $s, array $pagination = null, string $column = 'id', string $direction = 'desc') : Builder
    {
        if (!empty($pagination['sortBy'])) {
            $s = $s->orderBy(
                (string)$pagination['sortBy'],
                (string)$pagination['descending'] ? 'desc' : 'asc'
            );
        } else {
            $s = $s->orderBy(
                $column,
                $direction
            );
        }
        return $s;
    }


    /**
     * @param Builder $s
     * @param array|null $pagination
     * @return LengthAwarePaginator
     */
    protected static function standardPaginationStatic(Builder $s, array $pagination = null) : LengthAwarePaginator
    {
        if (!empty($pagination['rowsPerPage']) && $pagination['rowsPerPage'] > 0) {
            return $s->paginate((int)$pagination['rowsPerPage'], ['*'], 'page', $pagination['page']);
        }

        return $s->paginate(999999);
    }

    /**
     * @param LengthAwarePaginator $select
     * @param $items
     * @return array
     */
    protected function forcePagination(LengthAwarePaginator $select, $items) : array
    {
        return self::forcePaginationStatic($select, $items);
    }

    /**
     * @param LengthAwarePaginator $select
     * @param $items
     * @return array
     */
    public static function forcePaginationStatic(LengthAwarePaginator $select, $items): array
    {
        return [
            'current_page' => $select->currentPage(),
            'data' => $items,
            'first_page_url' => $select->url(1),
            'from' => (($select->currentPage() - 1) * $select->perPage()) + 1,
            'last_page' => $select->lastPage(),
            'last_page_url' => $select->url($select->currentPage()),
            'next_page_url' => $select->nextPageUrl(),
            'path' => $select->path(),
            'per_page' => $select->perPage(),
            'prev_page_url' => $select->previousPageUrl(),
            'to' => $select->currentPage() * $select->perPage(),
            'total' => $select->total(),
        ];
    }

}
