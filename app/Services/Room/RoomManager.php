<?php

namespace App\Services\Room;

use App\Services\Livekit\RoomService;
use App\Services\Livekit\TokenService;

class RoomManager {
    public function __construct(
        public RoomCache $roomCache,
        public RoomValidator $roomValidator,
        public RoomService $roomService,
        public TokenService $tokenService
    )
    {
    }

    public function createRoom(string $roomName) {
        if(!$this->roomValidator->canCreateRoom($roomName)){
            return [
                'ok' => false,
                'message' => 'cant create room'
            ];
        };

        $room = $this->roomService->createRoom($roomName);

        $sid = $room->getSid();
        $name = $room->getName();

        if (empty($sid) && empty($name)) {
            return [
                'ok' => false,
                'message' => 'livekit server err'
            ];
        }

        $this->roomCache->createRoom($roomName);

        return [
            'ok' => true,
            'message' => 'all success',
            'id' => $sid,
            'name' => $name
        ];
    }

    public function createToken(string $roomName , int $userId){
        if(!$this->roomValidator->canJoinRoom($roomName , $userId)) {
            return [
                'ok' => false,
                'message' => 'cant join room'
            ];
        }

        $token = $this->tokenService->createToken($roomName , $userId);

        return [
            'ok' => true,
            'token' => $token
        ];
    }

    public function removeRoom(string $roomName) {
        $this->roomCache->deleteRoom($roomName);
        return $this->roomService->deleteRoom($roomName);
    }

    public function busyUser() {
        return $this->roomCache->userList();
    }
}