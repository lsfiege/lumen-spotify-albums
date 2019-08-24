<?php

namespace App\Services;

use GuzzleHttp\Client;

class SpotifyService implements SpotifyContract
{
    private $authURL = 'https://accounts.spotify.com';

    private $baseURL = 'https://api.spotify.com';

    private $version = 'v1';

    private $token;

    private $client;

    public function __construct()
    {
        $this->token = $this->getToken();

        $this->client = new Client(['base_uri' => $this->baseURL]);
    }

    private function getToken()
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

        return json_decode($response->getBody()->getContents());
    }

    private function getEncodedCredentials()
    {
        $clientID = env('SPOTIFY_CLIENT_ID');

        $clientSecret = env('SPOTIFY_CLIENT_SECRET');

        return base64_encode("{$clientID}:{$clientSecret}");
    }

    public function searchArtist($artist, $allResults = false)
    {
        $response = $this->client->get("/{$this->version}/search?q={$artist}&type=artist", [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->token->access_token}",
            ],
        ]);

        $data = json_decode($response->getBody()->getContents());

        if (count($data->artists->items) === 0) {
            return null;
        }

        if ($allResults) {
            return $data->artists->items;
        }

        return $data->artists->items[0];
    }

    public function searchArtistAlbums($artistID)
    {
        $response = $this->client->get("/{$this->version}/artists/{$artistID}/albums", [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->token->access_token}",
            ],
        ]);

        $data = json_decode($response->getBody()->getContents());

        return $data->items;
    }
}
