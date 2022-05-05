<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    /**
     * @param Builder $s
     * @param Request $request
     * @param string $column
     * @param string $direction
     * @return Builder
     */
//    protected function standardOrderBy(Builder $s, Request $request, $column = 'id', $direction = 'desc') : Builder
//    {
//        return self::standardOrderByStatic($s, $request, $column, $direction);
//    }

    /**
     * @param Builder $s
     * @param Request $request
     * @return LengthAwarePaginator
     */
//    protected function standardPagination(Builder $s, Request $request) : LengthAwarePaginator
//    {
//        return self::standardPaginationStatic($s, $request);
//    }

    /**
     * @param Builder $s
     * @param Request $request
     * @param string $column
     * @param string $direction
     * @return Builder
     */
//    protected static function standardOrderByStatic(Builder $s, Request $request, $column = 'id', $direction = 'desc') : Builder
//    {
//        if (!empty($request->pagination['sortBy'])) {
//            $s = $s->orderBy(
//                (string)($request->pagination['sortBy']),
//                (string)($request->pagination['descending']) ? 'desc' : 'asc'
//            );
//        } else {
//            $s = $s->orderBy(
//                $column,
//                $direction
//            );
//        }
//        return $s;
//    }


    /**
     * @param Builder $s
     * @param Request $request
     * @return LengthAwarePaginator
     */
//    protected static function standardPaginationStatic(Builder $s, Request $request) : LengthAwarePaginator
//    {
//        if (!empty($request->pagination['rowsPerPage']) && $request->pagination['rowsPerPage'] > 0) {
//            return $s->paginate((int)$request->pagination['rowsPerPage'], ['*'], 'page', $request->pagination['page']);
//        }
//
//        return $s->paginate(999999);
//    }

    /**
     * @param LengthAwarePaginator $select
     * @param $items
     * @return array
     */
//    protected function forcePagination(LengthAwarePaginator $select, $items) : array
//    {
//        return self::forcePaginationStatic($select, $items);
//    }

    /**
     * @param LengthAwarePaginator $select
     * @param $items
     * @return array
     */
//    public static function forcePaginationStatic(LengthAwarePaginator $select, $items): array
//    {
//        return [
//            'current_page' => $select->currentPage(),
//            'data' => $items,
//            'first_page_url' => $select->url(1),
//            'from' => (($select->currentPage() - 1) * $select->perPage()) + 1,
//            'last_page' => $select->lastPage(),
//            'last_page_url' => $select->url($select->currentPage()),
//            'next_page_url' => $select->nextPageUrl(),
//            'path' => $select->path(),
//            'per_page' => $select->perPage(),
//            'prev_page_url' => $select->previousPageUrl(),
//            'to' => $select->currentPage() * $select->perPage(),
//            'total' => $select->total(),
//        ];
//    }

}
