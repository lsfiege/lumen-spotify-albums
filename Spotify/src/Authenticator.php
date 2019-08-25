<?php

namespace Spotify;

use Carbon\Carbon;
use GuzzleHttp\Client;

class Authenticator
{
    private $authURL = 'https://accounts.spotify.com';

    private $token;

    /** @var Carbon */
    private $lastUse;

    public function getToken()
    {
        if ($this->tokenHasExpired()) {
            $this->getValidToken();
        }

        return $this->token->access_token;
    }

    public function tokenHasExpired()
    {
        if (is_null($this->token)) {
            return true;
        }

        $seconds = Carbon::createFromFormat('s', $this->token->expires_in);

        $now = Carbon::now();

        if ($now->isAfter($this->lastUse->add($seconds))) {
            return true;
        }

        return false;
    }

    public function getValidToken()
    {
        $credentials = $this->getEncodedCredentials();

        $authenticator = new Client(['base_uri' => $this->authURL]);

        $response = $authenticator->request('POST', '/api/token', [
            'headers' => [
                'Authorization' => "Basic {$credentials}",
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'grant_type' => 'client_credentials',
            ],
        ]);

        $this->token = json_decode($response->getBody()->getContents());

        $this->lastUse = Carbon::now();
    }

    private function getEncodedCredentials()
    {
        $clientID = env('SPOTIFY_CLIENT_ID');

        $clientSecret = env('SPOTIFY_CLIENT_SECRET');

        return base64_encode("{$clientID}:{$clientSecret}");
    }
}
