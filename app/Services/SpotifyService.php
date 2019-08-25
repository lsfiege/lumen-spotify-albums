<?php

namespace App\Services;

use GuzzleHttp\Client;
use Spotify\Authenticator;

class SpotifyService implements SpotifyContract
{
    private $baseURL = 'https://api.spotify.com';

    private $version = 'v1';

    private $broker;

    private $token;

    private $client;

    public function __construct()
    {
        $this->broker = new Authenticator();

        $this->token = $this->broker->getToken();

        $this->client = new Client(['base_uri' => $this->baseURL]);
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
