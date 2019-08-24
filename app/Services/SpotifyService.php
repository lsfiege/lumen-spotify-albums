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

        $tokenObject = json_decode($response->getBody()->getContents());

        return $tokenObject->access_token;
    }

    private function getEncodedCredentials()
    {
        $clientID = env('SPOTIFY_CLIENT_ID');

        $clientSecret = env('SPOTIFY_CLIENT_SECRET');

        return base64_encode("{$clientID}:{$clientSecret}");
    }

    /**
     * @param $artist
     *
     * @return array
     */
    public function searchArtist($artist)
    {
        $response = $this->client->get("/{$this->version}/search?q={$artist}&type=artist", [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->token}",
            ],
        ]);

        $data = json_decode($response->getBody()->getContents());

        return $data->artists->items;
    }

    /**
     * @param $artistID
     *
     * @return array
     */
    public function searchArtistAlbums($artistID)
    {
        $response = $this->client->get("/{$this->version}/artists/{$artistID}/albums", [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => "Bearer {$this->token}",
            ],
        ]);

        $data = json_decode($response->getBody()->getContents());

        return $data->items;
    }
}
