<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    public int $status;
    public $errors;

    public function __construct(
        string $message,
        int $status = 400,
        $errors = null
    ) {
        parent::__construct($message);
        $this->status = $status;
        $this->errors = $errors;
    }
}
