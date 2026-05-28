<?php

namespace App\Helpers;

use App\Http\Resources\ResponseResource;

class ResponseHelper
{
    public static function success(string $message, mixed $data = null, int $status = 200)
    {
        return new ResponseResource(
            true,
            $message,
            MetaHelper::data($data),
            null,
            MetaHelper::meta($data),
            $status
        );
    }

    public static function error(string $message, array|null $errors = null, int $status = 400)
    {
        return new ResponseResource(
            false,
            $message,
            null,
            $errors,
            null,
            $status
        );
    }
}
