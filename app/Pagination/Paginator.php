<?php namespace App\Pagination;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator as BasePaginator;
use Illuminate\Contracts\Pagination\Presenter;

class Paginator extends BasePaginator
{
    public static function make($collection, $total, $perPage = 15)
    {
        return new LengthAwarePaginator($collection, $total, $perPage, $page, [
            'path'  => BasePaginator::resolveCurrentPath(),
        ]);
    }
}
