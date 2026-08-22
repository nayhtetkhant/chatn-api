<?php

namespace App\Services\Room;

class RoomValidator {
    public function __construct(
        public RoomCache $roomCache
    )
    {
    }

    public function canJoinRoom(string $roomName , int $userId) {
        if(!$this->roomCache->existRoom($roomName)) {
            return false;
        }

        if($this->roomCache->participantCount($roomName) >= config('services.livekit.max_room_members')){
            return false;
        }

        if($this->roomCache->getRoomByUser($userId)) {
            return false;
        }

        return true;
    }

    public function canCreateRoom(string $roomName) {
        if($this->roomCache->existRoom($roomName)) {
            return false;
        }

        return true;
    }

    public function canInviteUser(int $userId){
        if($this->roomCache->getRoomByUser($userId)) {
            return false;
        }

        return true;
    }
}