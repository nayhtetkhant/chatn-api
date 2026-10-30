<?php

namespace App\Events;

use App\Enums\Event;
use App\Models\Call;
use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CallAccept implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public array $accessUserIds,
        public int $userId,
        public string|null $token,
        public string|null $roomName,
        public Chat|null $chat,
        public Call|null $call
    )
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
        ];
    }

    public function broadcastAs() {
        return Event::CallAccept;
    }

    public function BroadcastWith() {
        return [
            'accessUserIds' => $this->accessUserIds,
            'token' => $this->token,
            'roomName' => $this->roomName,
            'chatId' => $this->chat->id,
            'callId' => $this->call->id
        ];
    }
}
