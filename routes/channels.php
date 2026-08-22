<?php

use App\Models\Chat;
use App\Models\User;
use App\Services\User\ChatService;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use Laravel\Reverb\Loggers\Log as LoggersLog;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{chatId}' , function($user  ,int $chatId) {
   return true;
});

Broadcast::channel('user.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('global', function (User $user) {
    return [
        'id'=> $user->id,
        'name' => $user->name
    ];
});