<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\v1\FriendGetFriendListRequest;
use App\Http\Requests\api\v1\FriendGetRequest;
use App\Http\Resources\api\v1\FriendListResource;
use App\Models\Friend;
use App\Services\User\FriendService;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function __construct(
        private FriendService $friendService
    )
    {
        
    }

    public function index(FriendGetFriendListRequest $request) {
        $friendList = $this->friendService->getFriendList($request->validated('type'));
        return successResponse(FriendListResource::collection($friendList));
    }
}
