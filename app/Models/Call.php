<?php

namespace App\Models;

use App\Enums\CallRequestStatus;
use App\Enums\CallType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

/**
 * @property int $id
 * @property int $call_request_id
 * @property string|null $started_at
 * @property string|null $ended_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $callParticipants
 * @property-read int|null $call_participants_count
 * @property-read \App\Models\CallRequest $callRequest
 * @method static \Database\Factories\CallFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call whereCallRequestId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call whereEndedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Call whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[Fillable('requester_id' ,'chat_id' ,'status' ,'type' , 'started_at' , 'ended_at')]
class Call extends Model
{
    /** @use HasFactory<\Database\Factories\CallFactory> */
    use HasFactory;

    #[Override]
    protected function casts()
    {
        return [
            'status' => CallRequestStatus::class,
            'type' => CallType::class
        ];
    }

    public function requester() {
        return $this->BelongsTo(User::class , 'requester_id' , 'id');
    }

    public function chat() {
        return $this->belongsTo(Chat::class);
    }


    public function callParticipants() {
        return $this->belongsToMany(User::class , 'call_participants' , 'call_id', 'user_id')->withTimestamps();
    }
}
