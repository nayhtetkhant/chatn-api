<?php

namespace App\Enums;

enum CallRequestStatus : string {
    case Pending = 'pending';
    case Accepted = 'Accepted';
    case Rejected = 'rejected';
    case TimeOut = 'timeOut';
}