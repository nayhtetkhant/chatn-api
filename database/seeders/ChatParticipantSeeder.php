<?php

namespace Database\Seeders;

use App\Enums\ChatType;
use App\Models\Chat;
use App\Models\ChatParticipant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChatParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chats = Chat::with('chatParticipants')->oldest()->get();

        foreach($chats as $chat) {
            if($chat->type == ChatType::P2P) {
                $uerId = User::inRandomOrder()->first()->id;
                $anotherUser = User::query()->whereNot('id' , $uerId)->inRandomOrder()->first()->id;

                $chat->chatParticipants()->syncWithoutDetaching([$uerId , $anotherUser]);
            }else{
                $userAId = User::inRandomOrder()->first()->id;
                $userBId = User::query()->whereKeyNot($userAId)->first()->id;
                $userCId = User::query()->whereKeyNot([$userAId  , $userBId])->first()->id;
                $userDId = User::query()->whereKeyNot([$userAId  , $userBId , $userCId])->first()->id;

                $chat->chatParticipants()->syncWithoutDetaching([$userAId , $userBId , $userCId , $userDId]);
            }
        }
    }
}
