<?php

namespace App\Enums;

enum FriendType : string {
    case CommonFriend = 'commonFriend';
    case BestFriend = 'bestFriend';
    case Partner = 'partner';
    case Family = 'family';
}