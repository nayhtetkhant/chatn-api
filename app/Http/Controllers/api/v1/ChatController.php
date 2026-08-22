<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\ChatStoreRequest;
use App\Http\Requests\api\v1\ChatUpdateRequest;
use App\Http\Resources\api\v1\ChatListResource;
use App\Http\Resources\api\v1\ChatResource;
use App\Models\Chat;
use App\Services\User\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(
        private ChatService $chatService
    )
    {

    }

    public function index() {
        return successResponse(
            ChatResource::collection(
                $this->chatService->chatList()
            )
        );
    }

    public function show(Chat $chat) {
        return successResponse(
            ChatResource::make(
                $this->chatService->chat($chat)
            )
        );
    }

    public function store(ChatStoreRequest $request) {
        return successResponse(
            ChatResource::make(
                $this->chatService->newChat([
                    "chat_name" => $request->validated('chatName'),
                    "type" => $request->validated('type'),
                    "display_name" => $request->validated('displayName')
                ] , $request->validated('participants'))
            )
        );
    }

    public function update(ChatUpdateRequest $request , Chat $chat) {
        return successResponse(
            ChatResource::make(
                $this->chatService->updateChat($chat , $request->validated())
            )
        );
    }
}
