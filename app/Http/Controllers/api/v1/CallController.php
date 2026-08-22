<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\CallAcceptRequest;
use App\Http\Requests\api\v1\CallInviteAcceptRequest;
use App\Http\Requests\api\v1\CallInviteRequest;
use App\Http\Requests\api\v1\CallOnHangUpRequest;
use App\Http\Requests\api\v1\CallStoreRequest;
use App\Http\Requests\api\v1\CallSuccessRequest;
use App\Http\Resources\api\v1\CallResource;
use App\Models\Call;
use App\Models\Chat;
use App\Services\User\CallService;
use Illuminate\Http\Request;

class CallController extends Controller
{
    public function __construct(
        public CallService $callService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CallStoreRequest $request , Chat $chat)
    {
        return successResponse(
            CallResource::make(
                $this->callService->newCall($chat , [
                    'requesterId' => $request->validated('requesterId')
                ])
            )
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function accept(Chat $chat , Call $call ,CallAcceptRequest $request) {
        return successResponse(
            $this->callService->acceptCall($chat , $call , $request->validated())
        );
    }

    public function success(Chat $chat , Call $call ,CallSuccessRequest $request ) {
        return successResponse(
            CallResource::make(
                $this->callService->successCall($chat , $call , $request->validated())
            )
        );
    }

    public function invite(Chat $chat , Call $call , CallInviteRequest $request) {
        return successResponse(
            $this->callService->inviteCall($chat , $call ,$request->validated())
        );
    }

    public function onHangUp(Chat $chat , Call $call ,CallOnHangUpRequest $request) {
        return successResponse(
            $this->callService->onHangUpCall($chat , $call , $request->validated())
        );
    }

    public function inviteAccept(Chat $chat , Call $call ,CallInviteAcceptRequest $request) {
        return successResponse(
            $this->callService->acceptInviteCall($chat , $call , $request->validated('roomName') , $request->validated('userId'))
        );
    }
}
