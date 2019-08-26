<?php

namespace App\Clients\Spotify;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class Authenticator
{
    private $authURL = 'https://accounts.spotify.com';

    private $token;

    /** @var Carbon */
    private $tokenExpiration;

    public function __construct()
    {
        $this->retrieveCache();
    }

    private function getEncodedCredentials()
    {
        $clientID = env('SPOTIFY_CLIENT_ID');

        $clientSecret = env('SPOTIFY_CLIENT_SECRET');

        return base64_encode("{$clientID}:{$clientSecret}");
    }

    private function getNewTokenExpiration()
    {
        return Carbon::now()->addSeconds($this->token->expires_in);
    }

    public function getToken()
    {
        if ($this->tokenHasExpired()) {
            $this->getValidToken();
        }

        return $this->token->access_token;
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

        $this->tokenExpiration = $this->getNewTokenExpiration();

        $this->refreshCache();
    }

    private function refreshCache()
    {
        $seconds = $this->token->expires_in;

        Cache::add('token', $this->token, $seconds);

        Cache::add('tokenExpiration', $this->tokenExpiration, $seconds);
    }

    private function retrieveCache()
    {
        $this->token = Cache::get('token');

        $this->tokenExpiration = Cache::get('tokenExpiration');
    }

    public function tokenHasExpired()
    {
        if (is_null($this->token)) {
            return true;
        }

        $now = Carbon::now();

        if ($now->isAfter($this->tokenExpiration)) {
            return true;
        }

        return false;
    }
}
