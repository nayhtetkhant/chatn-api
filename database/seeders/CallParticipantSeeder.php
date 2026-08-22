<?php

namespace Database\Seeders;

use App\Enums\CallRequestStatus;
use App\Models\Call;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CallParticipantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $calls = Call::with('chat' , 'callParticipants')->where('status' , CallRequestStatus::Accepted)->oldest()->get();

        foreach($calls as $call) {
            $users = $call->chat->chatParticipants()->inRandomOrder()->limit(rand(2,7))->pluck('users.id')->toArray();

            $call->callParticipants()->syncWithoutDetaching($users);
        }
    }
}
