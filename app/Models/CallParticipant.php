<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $call_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\CallParticipantFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CallParticipant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CallParticipant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CallParticipant query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CallParticipant whereCallId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CallParticipant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CallParticipant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CallParticipant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CallParticipant whereUserId($value)
 * @mixin \Eloquent
 */
#[Fillable('call_id' , 'user_id')]
class CallParticipant extends Model
{
    /** @use HasFactory<\Database\Factories\CallParticipantFactory> */
    use HasFactory;
}
