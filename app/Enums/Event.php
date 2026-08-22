<?php

namespace App\Enums;

enum Event : string {
    case ChatCreate = 'chat-create';
    case ChatUpdate = 'chat-update';
    case MessageCreate = 'message-create';
    case MessageUpdate = 'message-update';
    case CallCreate = 'call-create';
    case CallAccept = 'call-accept';
    case CallInvite = 'call-invite';
    case ChatListUpdate = 'chat-list-update';
}