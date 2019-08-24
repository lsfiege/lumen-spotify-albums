<?php

namespace App\Services;

interface SpotifyContract
{
    public function searchArtist($artist, $allResults = false);

    public function searchArtistAlbums($artistID);
}
