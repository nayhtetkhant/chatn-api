<?php

namespace App\Events;

use App\Enums\Event;
use App\Http\Resources\api\v1\ChatResource;
use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatCreate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        private Chat $chat
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
            new Channel('chat-public')
        ];
    }

    public function broadcastAs() {
        return Event::ChatCreate;
    }

    public function broadcastWith() {
        return [
            'chat' => ChatResource::make($this->chat)->additional(['hideParticipants' => true])->resolve()
        ];
    }
}
