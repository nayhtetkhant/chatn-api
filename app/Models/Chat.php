<?php

namespace App\Models;

use App\Enums\ChatType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Override;

#[Fillable('type' ,'chat_name' , 'display_name')]
class Chat extends Model
{
    /** @use HasFactory<\Database\Factories\ChatFactory> */
    use HasFactory;

    #[Override]
    protected function casts()
    {
        return[
            'type' => ChatType::class
        ];
    }

    public function calls() {
        return $this->hasMany(Call::class);
    }

    public function admins() {
        return $this->belongsToMany(User::class , 'admins')->withTimestamps();
    }

    public function chatParticipants() {
        return $this->belongsToMany(User::class , 'chat_participants' , 'chat_id', 'user_id')->withTimestamps();
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }

    public function lastedMessage() {
        return $this->hasOne(Message::class)->latestOfMany();
    }
}
