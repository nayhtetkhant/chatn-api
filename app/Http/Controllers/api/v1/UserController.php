<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\v1\UserResource;
use App\Models\User;
use App\Services\Room\RoomManager;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        public UserService $userService,
        public RoomManager $roomManager
    )
    {
        
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return successResponse(
            UserResource::make($user)
        );
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

    public function me() {
        return successResponse(
            UserResource::make(Auth::user())
        );
    }

    public function busy() {
        return successResponse(
            $this->roomManager->busyUser()
        );
    }
}
