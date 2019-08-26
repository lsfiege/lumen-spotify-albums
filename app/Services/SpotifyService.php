<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Spotify\Client;
use App\Exceptions\ArtistNotFoundException;

class SpotifyService implements SpotifyContract
{
    private $client;

    private $artists;

    private $albums;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param $artist
     *
     * @return array
     *
     * @throws ArtistNotFoundException
     */
    public function searchArtist($artist)
    {
        $data = $this->client->searchArtist($artist);

        if (empty($data->artists->items)) {
            throw new ArtistNotFoundException('artist not found');
        }

        $this->artists = $data->artists->items;

        return $this->artists;
    }

    /**
     * @param $artistID
     *
     * @return array
     */
    public function searchArtistAlbums($artistID)
    {
        $data = $this->client->searchArtistAlbums($artistID);

        $this->albums = $data->items;

        return $this->albums;
    }

    /**
     * @return \stdClass
     */
    public function firstArtist()
    {
        return Arr::first($this->artists);
    }
}
