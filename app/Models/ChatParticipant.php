<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable('chat_id' , 'user_id')]
class ChatParticipant extends Model
{
    /** @use HasFactory<\Database\Factories\ChatParticipantFactory> */
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }
}
