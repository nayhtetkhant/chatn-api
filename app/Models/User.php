<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $user_name
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Chat> $admins
 * @property-read int|null $admins_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Call> $callParticipants
 * @property-read int|null $call_participants_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CallRequest> $call_requests
 * @property-read int|null $call_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Chat> $chatParticipants
 * @property-read int|null $chat_participants_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $friendAsA
 * @property-read int|null $friend_as_a_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $friendAsB
 * @property-read int|null $friend_as_b_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $friendRequestApprovers
 * @property-read int|null $friend_request_approvers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $friendRequestInitiators
 * @property-read int|null $friend_request_initiators_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUserName($value)
 * @mixin \Eloquent
 */
#[Fillable(['name', 'email', 'password' , 'user_name'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable , HasApiTokens;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function calls() {
        return $this->hasMany(Call::class , 'requester_id' , 'id');
    }

    public function admins() {
        return $this->belongsToMany(Chat::class , 'admins' , 'user_id', 'chat_id' );
    }

    public function chatParticipants() {
        return $this->belongsToMany(Chat::class , 'chat_participants' , 'user_id', 'chat_id');
    }

    public function callParticipants() {
        return $this->belongsToMany(Call::class , 'call_participants' , 'user_id' , 'call_id')->withTimestamps();
    }

    public function friendRequestInitiators() {
        return $this->belongsToMany(self::class , 'friend_requests' , 'approver_id' , 'initiator_id')
                        ->withTimestamps()
                        ->withPivot('status');
    }

    public function friendRequestApprovers() {
        return $this->belongsToMany(self::class , 'friend_requests'  , 'initiator_id' , 'approver_id')
                        ->withTimestamps()
                        ->withPivot('status');
    }

    public function friendAsA() {
        return $this->belongsToMany(self::class , 'friends' , 'userA_id' , 'userB_id')
                        ->withTimestamps()
                        ->withPivot('type');
    }

    public function friendAsB() {
        return $this->belongsToMany(self::class , 'friends' , 'userB_id' , 'userA_id')
                        ->withTimestamps()
                        ->withPivot('type');
    }

    public function friends() {
        return collect([$this->friendAsA , $this->friendAsB])->collapse();
    }

    public function messages() {
        return $this->hasMany(Message::class , 'sender_id' , 'id');
    }
}
