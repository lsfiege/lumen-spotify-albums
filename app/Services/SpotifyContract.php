<?php

namespace App\Services;

interface SpotifyContract
{
    /**
     * @param $artist
     *
     * @return array
     */
    public function searchArtist($artist);

    /**
     * @param $artistID
     *
     * @return array
     */
    public function searchArtistAlbums($artistID);
}
