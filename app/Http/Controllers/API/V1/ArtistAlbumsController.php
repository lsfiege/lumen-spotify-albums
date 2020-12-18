<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlbumResource;
use App\Services\SpotifyContract;
use Illuminate\Http\Request;

class ArtistAlbumsController extends Controller
{
    /** @var SpotifyContract */
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

        try {
            $this->service->searchArtist($request->get('q'));
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

        $artist = $this->service->firstArtist();

        $albums = $this->service->searchArtistAlbums($artist->id);

        return AlbumResource::collection($albums);
    }
}
