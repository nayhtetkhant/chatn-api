<?php

namespace App\Services\User;

use App\Enums\CallRequestStatus;
use App\Events\CallAccept;
use App\Events\CallCreate;
use App\Events\CallInvite;
use App\Models\Call;
use App\Models\Chat;
use App\Services\Room\RoomCache;
use App\Services\Room\RoomManager;
use App\Services\Room\RoomValidator;
use Illuminate\Notifications\Events\BroadcastNotificationCreated;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class CallService {
    public function __construct(
        public ChatService $chatService,
        public RoomManager $roomManager,
        public RoomValidator $roomValidator,
        public RoomCache $roomCache,
    )
    {
    }

    public function newCall(Chat $chat ,array $data ){
        $this->chatService->isAccessChat($chat , [$data['requesterId']]);
        $call =  Call::query()->create([
            'chat_id' => $chat->id,
            'requester_id' => $data['requesterId'],
            'status' => CallRequestStatus::Pending,
            'started_at' => Date::now(),
            'ended_at' => null
        ]);

        $chat->load('chatParticipants');

        $roomName = 'room-' . $chat->id . "-" . $call->id;
        $livekitRoom = $this->roomManager->createRoom($roomName);

        $chat->chatParticipants->each(function ($participant) use($call , $livekitRoom) {
            if(!$this->roomValidator->canInviteUser($participant->id)) {
                return;
            }
            broadcast(new CallCreate($participant->id ,$livekitRoom['name']  , $call));
        });

        return $call;
    }

    public function acceptCall(Chat $chat, Call $call, array $data) 
    {
        $accessUserIds = [$data['requesterId'], $data['accepterId']];

        $this->chatService->isAccessChat($chat, $accessUserIds);

        $busyUserIds = (array) $this->roomManager->busyUser();

        $freeUsers = $chat->chatParticipants()
            ->whereNotIn('id', $busyUserIds)
            ->get();

        foreach ($freeUsers as $user) {
            if (in_array($user->id, $accessUserIds)) {
                $token = $this->roomManager->createToken($data['roomName'], $user->id);

                if (! isset($token['ok']) || ! $token['ok']) {
                    return [
                        'ok' => false,
                        'message' => "Failed to create token for user {$user->id}"
                    ];
                }

                broadcast(new CallAccept(
                    $accessUserIds,
                    $user->id,
                    $token['token'],
                    $data['roomName'],
                    $chat,
                    $call
                ));
            } else {
                broadcast(new CallAccept(
                    $accessUserIds,
                    $user->id,
                    null,
                    null,
                    null,
                    null
                ));
            }
        }

        return [
            'ok' => true
        ];
    }

    public function acceptInviteCall(Chat $chat , Call $call , string $roomName ,int $userId) {
        $token = $this->roomManager->createToken($roomName , $userId);
        if(!$token['ok']) {
            return [
                'ok' => 'livekit err here'
            ];
        }
        broadcast(new CallAccept([$userId] , $userId , $token['token'] , $roomName , $chat , $call));

        return [
            'ok' => true
        ];
        
    }

    public function successCall(Chat $chat , Call $call , array $data) {
        $this->chatService->isAccessChat($chat , [$data['participantId']]);
        $res = DB::transaction(function () use ($call , $data) {
            $call->update([
                'status' => CallRequestStatus::Accepted,
                'started_at' => now(),
            ]);
            $call->callParticipants()->syncWithoutDetaching((array) [$data['participantId']]);

            return $call->fresh();
        });

        if($res) {
            $this->roomCache->addParticipant($data['roomName'] , $data['participantId']);
        }
        return $res;
    }

    public function inviteCall(Chat $chat , Call $call , array $data) {
        $this->chatService->isAccessChat($chat , [$data['participantId']]);
        if(!$this->roomValidator->canInviteUser($data['participantId'])) {
            return [
                'ok' => false
            ];
        }
        broadcast(new CallInvite($data['participantId'], $data['roomName'] , $call));
        return [
            'ok' => true
        ];
    }

    public function onHangUpCall(Chat $chat, Call $call, array $data) {
        $this->chatService->isAccessChat($chat, [$data['participantId']]);
        $this->roomCache->deleteParticipant($data['roomName'], $data['participantId']);
        $remainingParticipants = $this->roomCache->participantCount($data['roomName']);
        if ($remainingParticipants <= 0) {
            $this->roomManager->removeRoom($data['roomName']);

            if (is_null($call->ended_at)) {
                $call->update([
                    'ended_at' => now()
                ]);
            }
        }

        return [
            'ok' => true
        ];
    }
}