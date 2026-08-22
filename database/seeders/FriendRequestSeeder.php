<?php

namespace Database\Seeders;

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FriendRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $a = 0;
        while ($a <= 100) {
            try {
                $initiatorId = User::inRandomOrder()->first()->id;
                $approverId = User::query()->whereNot('id' , $initiatorId)->inRandomOrder()->first()->id;

                $isDuplicated =  FriendRequest::query()->where('initiator_id' , $approverId)->where('approver_id' , $initiatorId) ->first();
                if($isDuplicated) {
                    continue;
                }

                FriendRequest::factory()->create([
                    'initiator_id' => $initiatorId,
                    'approver_id' => $approverId
                ]);
                $a++;
            } catch (\Throwable $th) {
            }
        }
    }
}
