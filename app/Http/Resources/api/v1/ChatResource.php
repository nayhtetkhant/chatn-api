<?php

namespace App\Http\Resources\api\v1;

use App\Enums\ChatType;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'chatName' => $this->resource->chat_name,
            'type' => $this->resource->type,
            'displayName' => $this->whenLoaded('chatParticipants' , function () {
                if($this->resource->type === ChatType::Group) {
                    return $this->resource->display_name ?? $this->resource->chat_name;
                }
                $friend = collect($this->resource->chatParticipants)->first(function ($person) {
                    return $person->id !== Auth::id();
                });
                return $friend ? $friend->name : 'unknown friend';
            }),
            'lastedMessage' => MessageResource::make($this->whenLoaded('lastedMessage')),
            'chatParticipants' => ParticipantResource::collection($this->whenLoaded('chatParticipants'))
        ];
    }
}
