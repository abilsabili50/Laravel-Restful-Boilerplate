<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class MetaHelper
{
    public static function data(mixed $data)
    {
        if ($data instanceof LengthAwarePaginator) {
            return $data->items();
        }

        if ($data instanceof Collection) {
            return $data;
        }

        return $data;
    }

    public static function meta(mixed $data): ?array
    {
        if (!$data instanceof LengthAwarePaginator) {
            return null;
        }

        return [
            'current_page'  => $data->currentPage(),
            'per_page'      => $data->perPage(),
            'total'         => $data->total(),
            'last_page'     => $data->lastPage(),
            'from'          => $data->firstItem(),
            'to'            => $data->lastItem()
        ];
    }
}
