<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\MessageStoreRequest;
use App\Http\Requests\api\v1\MessageStroeRequest;
use App\Http\Resources\api\v1\MessageResource;
use App\Models\Chat;
use App\Services\User\MessageService;
use Illuminate\Http\Request;

class MessageController extends Controller
{

    public function __construct(
        private MessageService $messageService
    )
    {
        
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Chat $chat)
    {
        return MessageResource::collection(
                $this->messageService->MessageList($chat)
            );

    }

    /**
     * update a newly created resource in storage.
     */
    public function update(Chat $chat , )
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * store the specified resource in storage.
     */
    public function store(MessageStoreRequest $request, Chat $chat)
    {
        return successResponse(
            MessageResource::make(
                $this->messageService->newMessage($chat , [
                    'type' => $request->validated('type'),
                    'message' => $request->validated('message'),
                    'sender_id' => $request->validated('senderId')
                ])
            )
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
