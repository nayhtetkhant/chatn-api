<?php

namespace App\Services\Livekit;

use Agence104\LiveKit\RoomCreateOptions;
use Agence104\LiveKit\RoomServiceClient;

class RoomService {
    private $svcInstance = null;

    public function svc(){
        if($this->svcInstance) {
            return $this->svcInstance;
        }
        $host = config('services.livekit.host');
        $api = config('services.livekit.api_key');
        $secret_key = config('services.livekit.secret_key');
        $this->svcInstance = new RoomServiceClient($host , $api , $secret_key);    
        return $this->svcInstance;
    }

    public function createRoom(string $roomName) {
       $opts = (new RoomCreateOptions())
                ->setName($roomName)
                ->setEmptyTimeout(10)
                ->setMaxParticipants((int) config('services.livekit.max_room_members'));

        return $this->svc()->createRoom($opts);
    }

    public function roomList() {
        return $this->svc()->listRooms();
    }

    public function deleteRoom(string $roomName) {
        return $this->svc()->deleteRoom($roomName);
    }
}