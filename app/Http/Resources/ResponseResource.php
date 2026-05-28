<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

class ResponseResource extends JsonResource
{
    public $status;
    public $message;
    public $resource;
    public $error;
    public $meta;
    public $statusCode;

    #[Override]
    public function __construct($status, $message, $resource = null, $error = null, $meta = null, $statusCode = 200)
    {
        return parent::__construct($resource);
        $this->status = $status;
        $this->message = $message;
        $this->resource = $resource;
        $this->error = $error;
        $this->meta = $meta;
        $this->statusCode = $statusCode;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->resource,
            'meta' => $this->meta,
            'error' => $this->error
        ];
    }

    public function withResponse(Request $request, JsonResponse $response)
    {
        $response->setStatusCode($this->statusCode);
    }
}
