<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if(app()->environment('local')) {
            $this->call([
                UserSeeder::class,
                FriendRequestSeeder::class,
                FriendSeeder::class,
                ChatSeeder::class,
                ChatParticipantSeeder::class,
                AdminSeeder::class,
                MessageSeeder::class,
                CallSeeder::class,
                CallParticipantSeeder::class
            ]);
        }
    }
}
