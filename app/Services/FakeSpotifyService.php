<?php

namespace App\Services;

use Spotify\Exceptions\ArtistNotFoundException;

class FakeSpotifyService implements SpotifyContract
{
    public $artist;

    public $albums;

    public function __construct()
    {
        $this->artist = [
            new FakeArtist(),
        ];

        $this->albums = [
            new FakeAlbum(),
        ];
    }

    public function searchArtist($artist)
    {
        if ($this->artist[0]->name !== $artist) {
            throw new ArtistNotFoundException('artist not found');
        }

        return $this->artist;
    }

    public function searchArtistAlbums($artistID)
    {
        if ($this->artist[0]->id !== $artistID) {
            return null;
        }

        return $this->albums;
    }

    public function firstArtist()
    {
        return $this->artist[0];
    }
}

class FakeArtist
{
    public $id = 'k$2asljf3j4lkjrlewjflwk342';

    public $name = 'audioslave';
}

class FakeAlbum
{
    public $name = 'Album Name';

    public $release_date = '10-10-2010';

    public $total_tracks = 10;

    public $type = "album";

    public $uri = "spotify:album:76fYJtMmnPTOpipCoH1Mgo";

    public $images = [
        [
            "height" => 640,
            "width" => 640,
            "url" => "https://i.scdn.co/image/6c951f3f334e05ffa",
        ],
    ];
}
