<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\AuthRequest;
use App\Services\User\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    )
    { 
    }

    public function login(AuthRequest $request) {
        $result =  $this->authService->login(
            $request->validated('userName'),
            $request->validated('password')
        );

        if(!$result) {
            return successResponse('login failed');
        }

        return successResponse($result);
    }
}
