<?php

namespace App\Models;

use App\Enums\MessageType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Override;

#[Fillable('chat_id' , 'sender_id' , 'message' , 'type')]
class Message extends Model
{
    /** @use HasFactory<\Database\Factories\MessageFactory> */
    use HasFactory;

    #[Override]
    protected function casts()
    {
        return [
            'type' => MessageType::class
        ];
    }

    public function chat() {
        return $this->belongsTo(Chat::class);
    }

    public function sender() {
        return $this->belongsTo(User::class , 'sender_id' , 'id');
    }
}
