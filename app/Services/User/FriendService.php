<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FriendService {
    public function getFriendList(string $type) {
        $user = Auth::user()    ;
        if($type == 'all') {
            return $user->friends();
        }
        return collect([
            $user->friendAsA()->wherePivot('type' , $type)->get(),
            $user->friendAsB()->wherePivot('type' , $type)->get()
        ])->collapse();
    }
}