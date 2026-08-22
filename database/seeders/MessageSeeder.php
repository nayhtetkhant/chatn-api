<?php

namespace Database\Seeders;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chats = Chat::query()->with('chatParticipants')->oldest()->get('id');

        foreach($chats as $chat) {

            $mCount = 501;

            Message::factory()->count(500)->create([
                'sender_id' => function () use ($chat) {
                    return $chat->chatParticipants()
                                ->inRandomOrder()
                                ->value('users.id');
                },
                'chat_id' => $chat->id , 
                'message' => function () use (&$mCount) {
                    $mCount--;
                    return $mCount;
                }
            ]);
        }
    }
}
