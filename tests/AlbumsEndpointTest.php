<?php

class AlbumsEndpointTest extends TestCase
{
    /** @test */
    public function it_can_retrieve_albums_of_artist()
    {
        $this->app->bind(
            App\Services\SpotifyContract::class,
            App\Services\FakeSpotifyService::class
        );

        $response = $this->get('/api/v1/albums?q=audioslave');

        $response->seeJson([
            [
                "name" => "Album Name",
                "released" => "10-10-2010",
                "tracks" => 10,
                "cover" => [
                    "height" => 640,
                    "width" => 640,
                    "url" => "https://i.scdn.co/image/6c951f3f334e05ffa",
                ],
            ],
        ]);
    }

    /** @test */
    public function it_return_an_not_found_response_if_not_found_artist()
    {
        $this->app->bind(
            App\Services\SpotifyContract::class,
            App\Services\FakeSpotifyService::class
        );

        $response = $this->get('/api/v1/albums?q=notaband');

        $response->seeJson([
            'error' => 'artist not found',
        ])->assertResponseStatus(404);
    }
}
