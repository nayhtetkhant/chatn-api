<?php

namespace App\Http\Resources\api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CallResource extends JsonResource
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
            'requesterId' => $this->resource->requester_id,
            'chatId' => $this->resource->chat_id,
            'status' => $this->resource->status,
            'startedAt' => $this->resource->started_at,
            'endedAt' => $this->resource->ended_at,
            'callParticipants' =>   $this->whenLoaded('callParticipants' , function () {
                return ParticipantResource::collection($this->resource->callParticipants);
            })
        ];
    }
}
