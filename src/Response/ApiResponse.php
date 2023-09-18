<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{
    public function __construct($data = null, int $status = 200, array $headers = [], bool $json = false)
    {
        parent::__construct($data, $status, $headers, $json);
    }

    public static function success(array $data, int $status = 200, array $headers = []): ApiResponse
    {
        $data["success"] = true;
        return new self(json_encode($data), $status, $headers, true);
    }

    public static function failure(array $data, int $status = 500, array $headers = []): ApiResponse
    {
        $data["success"] = false;
        return new self(json_encode($data), $status, $headers, true);
    }
}