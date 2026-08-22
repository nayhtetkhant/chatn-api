<?php

namespace App\Models;

use App\Enums\FriendRequestStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Override;

/**
 * @property int $id
 * @property int $initiator_id
 * @property int $approver_id
 * @property FriendRequestStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\FriendRequestFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FriendRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FriendRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FriendRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FriendRequest whereApproverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FriendRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FriendRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FriendRequest whereInitiatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FriendRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FriendRequest whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[Fillable('initiator_id' ,'approver_id' ,'status')]
class FriendRequest extends Model
{
    /** @use HasFactory<\Database\Factories\FriendRequestFactory> */
    use HasFactory;

    #[Override]
    protected function casts():array
    {
        return [
            'status' => FriendRequestStatus::class
        ];
    }
}
