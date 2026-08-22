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
        public int $userId,
        public string $token,
        public string $roomName,
        public Chat $chat,
        public Call $call
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
            'token' => $this->token,
            'roomName' => $this->roomName,
            'chatId' => $this->chat->id,
            'callId' => $this->call->id
        ];
    }
}
