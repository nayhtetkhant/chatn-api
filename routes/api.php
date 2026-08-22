<?php

use App\Http\Controllers\api\v1\AuthController;
use App\Http\Controllers\api\v1\CallController;
use App\Http\Controllers\api\v1\ChatController;
use App\Http\Controllers\api\v1\FriendController;
use App\Http\Controllers\api\v1\MessageController;
use App\Http\Controllers\api\v1\UserController;
use App\Models\Call;
use App\Services\Livekit\LiveKitTokenService;
use App\Services\Livekit\RoomService;
use App\Services\Livekit\TokenService;
use App\Services\Room\RoomManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::get('me' , [UserController::class , 'me'])
            ->middleware('auth:sanctum');
        Route::post('logout', [AuthController::class, 'logout'])
            ->middleware('auth:sanctum');
    });

    Route::middleware('auth:sanctum')->group(function () {

        Route::get('users/busy' , [UserController::class , 'busy']);

        Route::apiResource('users', UserController::class);

        Route::apiResource('friends', FriendController::class)
            ->only(['index', 'store', 'destroy']);

        Route::apiResource('chats', ChatController::class);

        Route::apiResource('chats.messages', MessageController::class);

        Route::apiResource('chats.calls' , CallController::class);

        Route::post('chats/{chat}/calls/{call}/accept' , [CallController::class , 'accept']);

        Route::post('chats/{chat}/calls/{call}/success' , [CallController::class , 'success']);
        
        Route::post('chats/{chat}/calls/{call}/onHangUp' , [CallController::class , 'onHangUp']);

        Route::post('chats/{chat}/calls/{call}/invite' , [CallController::class , 'invite']);

        Route::post('chats/{chat}/calls/{call}/inviteAccept' , [CallController::class , 'inviteAccept']);
    });
});
