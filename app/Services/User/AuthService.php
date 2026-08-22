<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService {
    public function login(string $userName , string $password) {
        $user = User::query()->where('user_name' , $userName)->first();

        if(!$user || !Hash::check($password , $user->password)) {
            return ['isSuccess' => false];
        }

        return [
            'isSuccess' => true,
            'id' => $user->id,  
            'token' => $user->createToken('auth_token')->plainTextToken,
        ];
    }
}