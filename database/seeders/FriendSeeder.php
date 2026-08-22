<?php

namespace Database\Seeders;

use App\Models\Friend;
use App\Models\FriendRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FriendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $friendReqAcceptedIds = FriendRequest::query()->where('status' , 'accepted')->get(['initiator_id' , 'approver_id']);
        
        foreach ($friendReqAcceptedIds as $reqId) {
            $isGreaterThan = $reqId->initiator_id > $reqId->approver_id;
            Friend::factory()->create([
                'userA_id' => $isGreaterThan ? $reqId->approver_id : $reqId->initiator_id,
                'userB_id' => $isGreaterThan ? $reqId->initiator_id : $reqId->approver_id
            ]);
        };
    }
}
