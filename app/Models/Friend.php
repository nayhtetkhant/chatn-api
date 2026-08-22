<?php

namespace App\Models;

use App\Enums\FriendType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Override;

/**
 * @property int $id
 * @property int $userA_id
 * @property int $userB_id
 * @property FriendType $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\FriendFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friend newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friend newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friend query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friend whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friend whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friend whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friend whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friend whereUserAId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Friend whereUserBId($value)
 * @mixin \Eloquent
 */
#[Fillable('userA_id' ,'userB_id' ,'type')]
class Friend extends Model
{
    /** @use HasFactory<\Database\Factories\FriendFactory> */
    use HasFactory;

    #[Override]
    protected function casts()
    {
        return [
            'type' => FriendType::class
        ];
    }
}
