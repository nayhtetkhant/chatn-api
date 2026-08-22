<?php

namespace App\Services\Room;

use Illuminate\Support\Facades\Redis;
use Rubix\ML\NeuralNet\ActivationFunctions\ReLU;

class RoomCache {
    public function roomList() {
       return Redis::sMembers('rooms:list');
    }

    public function userList(): array
    {
        $users = [];

        foreach ($this->roomList() as $room) {
            $users = array_merge($users, $this->getUserByRoom($room));
        }

        return array_values(array_map('intval', array_unique($users)));
    }

    public function participantCount(string $roomName) {
        return Redis::scard('room:' . $roomName); 
    }

    public function createRoom(string $roomName) {
        Redis::sAdd("rooms:list" , $roomName);
    }

    public function deleteRoom(string $roomName) {
        Redis::srem('rooms:list' , $roomName);
        Redis::del('room:' . $roomName);

        $usersInRoom = $this->getUserByRoom($roomName);
        foreach ($usersInRoom as $userId) {
            Redis::srem('user:' . $userId, $roomName);
        }
    }

    public function addParticipant(string $roomName  ,int $userId){
        Redis::sAdd('room:' . $roomName , $userId);
        Redis::sAdd('user:' . $userId , $roomName);
    }

    public function deleteParticipant(string $roomName , int $userId){
        Redis::srem('room:' . $roomName , $userId);
        Redis::srem('user:' . $userId , $roomName);
    }

    public function existRoom(string $roomName){
        return Redis::sismember('rooms:list' , $roomName);
    }

    public function existUserByRoom(string $roomName , int $userId){
        return Redis::sismember('room:' . $roomName , $userId);
    }

    public function getUserByRoom(string $roomName){
        return Redis::sMembers('room:' . $roomName);
    }

    public function getRoomByUser(int $userId) {
        return Redis::sMembers('user:' .$userId);
    }
}