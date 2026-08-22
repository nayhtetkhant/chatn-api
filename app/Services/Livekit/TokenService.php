<?php

namespace App\Services\Livekit;

use Agence104\LiveKit\AccessToken;
use Agence104\LiveKit\AccessTokenOptions;
use Agence104\LiveKit\VideoGrant;

class TokenService {
    public function createToken(string $roomName , string $participant) {
        $api = config('services.livekit.api_key');
        $secret_key = config('services.livekit.secret_key');

        $tokenOptions = (new AccessTokenOptions())->setIdentity($participant);
        $videoGrant = (new VideoGrant())
                        ->setRoomJoin()
                        ->setRoomName($roomName)
                        ->setCanPublish(true)
                        ->setCanSubscribe(true);
        $token = (new AccessToken($api, $secret_key))->init($tokenOptions)->setGrant($videoGrant)->toJwt();

        return $token;
    }
}