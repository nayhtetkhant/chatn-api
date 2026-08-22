<?php

namespace App\Utils;

final class Response {
    public const string CODE_SUCCESS = 'success';

    public const string CODE_ERROR = 'error';

    public static function success(
        mixed $data = null,
        int $status = 200,
        ?string $message = null
    ) {
        return response()->json(
            [
                'success' => true,
                'code' => self::CODE_SUCCESS,
                'message' => $message ?? 'Success',
                'data' => $data
            ],
            $status
        );
    }
}