<?php

namespace Database\Seeders;

use App\Enums\ChatType;
use App\Models\Admin;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $GroupChats = Chat::with(['admins' , 'chatParticipants'])->where('type' , ChatType::Group)->oldest()->get();

        foreach($GroupChats as $chat) {
            $adminId = $chat->chatParticipants()->inRandomOrder()->value('users.id');
            $chat->admins()->syncWithoutDetaching($adminId);
        }
    }
}
