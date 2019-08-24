<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Services\SpotifyContract;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ArtistAlbumsController extends Controller
{
    private $service;

    public function __construct(SpotifyContract $service)
    {
        $this->service = $service;
    }

    public function get(Request $request)
    {
        $this->validate($request, [
            'q' => 'required|string',
        ]);

        $artists = $this->service->searchArtist($request->get('q'));

        if (empty($artists)) {
            return response()->json(['error' => 'artist not found'], 404);
        }

        $artist = Arr::first($artists);

        $albums = $this->service->searchArtistAlbums($artist->id);

        $albums = $this->formatAlbumsResponse($albums);

        return response()->json($albums);
    }

    /**
     * @param array $albums
     *
     * @return array|\Illuminate\Support\Collection
     */
    private function formatAlbumsResponse(array $albums)
    {
        if (empty($albums)) {
            return $albums;
        }

        $albums = collect($albums);

        $response = $albums->map(function ($album) {
            return [
                'name' => $album->name,
                'released' => $album->release_date,
                'tracks' => $album->total_tracks,
                'cover' => $album->images[0],
            ];
        });

        return $response;
    }
}
