<?php

namespace Database\Seeders;

use App\Models\Call;
use App\Models\Chat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chats = Chat::query()->with('chatParticipants')->get();

        foreach($chats as $chat){
            Call::factory()->count(rand(1,4))->create([
                'chat_id' => $chat->id,
                'requester_id' => function () use($chat) {
                    return $chat->chatParticipants()
                                ->inRandomOrder()
                                ->value('users.id');
                }
            ]);
        }
    }
}
