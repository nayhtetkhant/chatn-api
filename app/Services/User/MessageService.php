<?php

namespace App\Services\User;

use App\Events\ChatListUpdate;
use App\Events\MessageCreate;
use App\Models\Chat;
use Error;

class MessageService {
    public function __construct(
        private ChatService $chatService
    )
    {
        
    }

    public function MessageList(Chat $chat) {
        $this->chatService->isAccessChat($chat);
        return $chat->messages()
                    ->orderBy('id', 'desc')
                    ->cursorPaginate(30);
    }

    public function newMessage(Chat $chat ,array $data) {
        $this->chatService->isAccessChat($chat , [$data['sender_id']]);
        $message =  $chat->messages()
                    ->create([
                        ...$data,
                        'chat_id' => $chat->id
                    ]);

        broadcast(new MessageCreate($message));
        foreach($chat->chatParticipants as $participant) {
            broadcast(new ChatListUpdate($participant->id));
        }
        return $message;
    }
}