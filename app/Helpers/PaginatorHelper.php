<?php

namespace App\Helpers;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginatorHelper
{
    public static function convertArrToPagination($item, $perPage = 50, $totalItem, $page = 1, $options = []): LengthAwarePaginator
    {
        $options['onEachSide'] = 1;
        return new LengthAwarePaginator(collect($item) , $totalItem, $perPage, $page, $options);
    }

    public static function paginateArray($items, $perPage, $page = null)
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = new Collection($items);
        $total = $items->count();
        $results = $items->forPage($page, $perPage);
        $paginator = new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
        ]);
        return $paginator;
    }
}
