<?php

namespace App\Services;

use GuzzleHttp\Client;

class SpotifyService
{
    private $authenticator;

    private $token;

    private $client;

    public function __construct()
    {
        $clientID = env('SPOTIFY_CLIENT_ID');
        $clientSecret = env('SPOTIFY_CLIENT_SECRET');
        $credentials = base64_encode("{$clientID}:{$clientSecret}");

        $this->authenticator = new Client(['base_uri' => 'https://accounts.spotify.com']);

        $response = $this->authenticator->request('POST', '/api/token', [
            'headers' => [
                'Authorization' => "Basic {$credentials}",
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'grant_type' => 'client_credentials',
            ],
        ]);

        $this->token = json_decode($response->getBody()->getContents());

        $this->client = new Client(['base_uri' => 'https://api.spotify.com']);
    }

    public function searchArtist($artist, $allResults = false)
    {
        $response = $this->client->get("/v1/search?q={$artist}&type=artist", [
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
        $response = $this->client->get("/v1/artists/{$artistID}/albums", [
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
