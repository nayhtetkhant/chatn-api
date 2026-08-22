<?php

use App\Utils\Response;

if(!function_exists('successResponse')) {
    function successResponse(mixed $data = null, int $status = 200, ?string $message = null) {
        return Response::success($data, $status, $message);
    }
}