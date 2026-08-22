<?php

namespace App\Services\User;

use App\Enums\ChatType;
use App\Enums\MessageType;
use App\Events\ChatCreate;
use App\Events\ChatListUpdate;
use App\Events\ChatUpdate;
use App\Models\Chat;
use App\Models\User;
use Error;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatService {
    
    public function isAccessChat(Chat $chat ,?array $accessedIds = null ) {
        $idsToCheck = array_filter([Auth::id() , ...($accessedIds ?? [])]);
        $participantIds = $chat->chatParticipants()->pluck('user_id')->toArray();
        $diffedIds = array_diff($idsToCheck , $participantIds);
        
        if(empty($diffedIds)){
            return;
        }
        if(in_array(Auth::id() , $diffedIds)){
            throw new Error('This chat cannot be accessed by auth user');
        }
        throw new Error('This chat cannot be accessed');
    }

    public function chatList() {
        $chats = Auth::user()
            ->chatParticipants()
            ->with(['chatParticipants' , 'lastedMessage'])
            ->get();

        return $chats;
    }

    public function chat(Chat $chat) {
        $this->isAccessChat($chat);

        $chat =  $chat->load('chatParticipants');

        return $chat;
    }

    public function newChat(array $data ,array $participantIds) {
        $chat =  DB::transaction(function() use($data , $participantIds) {
            $chat =  Chat::query()->create($data);
            $chat->chatParticipants()->sync($participantIds);
            $chat->messages()->create([
                'chat_id' => $chat->id,
                'sender_id' => Auth::id(),
                'message' => 'chat is started by' . User::query()->whereId(Auth::id())->first()->name,
                'type' => MessageType::Text
            ]);
            return $chat;
        });
        $chat->load(['chatParticipants', 'lastedMessage']);
        foreach($chat->chatParticipants as $participant) {
            broadcast(new ChatListUpdate($participant->id));
        }
        return $chat;
    }

    public function updateChat(Chat $chat ,array $data) {
        $this->isAccessChat($chat);
        $chat->update([
            'display_name' => $chat->type === ChatType::P2P ? null : ($data['displayName'] ?? $chat->display_name),
            'chat_name' => $data['chatName'] ?? $chat->chat_name
        ]);
        $chat->load('chatParticipants');
        broadcast(new ChatUpdate($chat));
        return $chat;

    }
}