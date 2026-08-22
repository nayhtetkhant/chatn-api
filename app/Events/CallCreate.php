<?php

namespace App\Events;

use App\Enums\Event;
use App\Http\Resources\api\v1\CallResource;
use App\Models\Call;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Laravel\Reverb\Loggers\Log;

class CallCreate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public string $userId,
        public string $roomName,
        public Call $call,
    )
    {
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
        return Event::CallCreate;
    }

    public function broadcastWith() {
        return [
            'call' => CallResource::make($this->call)->resolve(),
            'roomName' => $this->roomName
        ];
    }
}
