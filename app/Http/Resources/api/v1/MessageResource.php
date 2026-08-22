<?php

namespace App\Http\Resources\api\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'senderId' => $this->resource->sender_id,
            'message' => $this->resource->message,
            'type' => $this->resource->type,
            'updatedAt' => $this->resource->updated_at
        ];
    }
}
