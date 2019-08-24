<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Services\SpotifyService;
use Illuminate\Http\Request;

class ArtistAlbumsController extends Controller
{
    /** @var SpotifyService */
    private $service;

    public function __construct(SpotifyService $service)
    {
        $this->service = $service;
    }

    public function get(Request $request)
    {
        $this->validate($request, [
            'q' => 'required|string',
        ]);

        $artist = $this->service->searchArtist($request->get('q'));

        if (is_null($artist)) {
            return response()->json(['error' => 'artist not found'], 404);
        }

        $albums = $this->service->searchArtistAlbums($artist->id);

        // Format response

        return response()->json($albums);
    }
}
