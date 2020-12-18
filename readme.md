# Spotify Artist Albums Endpoint demo

This demo app was made with [Lumen](https://lumen.laravel.com/), a PHP microframework from [Laravel](https://laravel.com/)

---

## Setup
1. clone the repo and enter into project folder
2. cp `.env.example` to `.env` file and fill the `SPOTIFY_CLIENT_ID` and `SPOTIFY_CLIENT_SECRET` fields
3. run `composer install`

## Usage
Into the project folder, start a server by running the following command:
```bash
php -S localhost:80 -t public
```

>Note: you can use any port number

Now you can send a GET request with the name of the artist you want to get their albums from:

```bash
curl -X GET \
  'http://localhost/api/v1/albums?q=audioslave' \
  -H 'Accept: application/json'
```

Also you can use the cURL output with jq to get a pretty print of results

```bash
curl -X GET \
  'http://localhost/api/v1/albums?q=audioslave' \
  -H 'Accept: application/json' | jq
```

```json
{
    "data": [
        {
            "name": "Revelations",
            "released": "2006-09-05",
            "tracks": 13,
            "cover": {
                "height": 640,
                "url": "https://i.scdn.co/image/ab67616d0000b2734c4ee2bf4293fb52f78726af",
                "width": 640
            }
        },
        {
            "name": "Revelations",
            "released": "2006-09-01",
            "tracks": 13,
            "cover": {
                "height": 640,
                "url": "https://i.scdn.co/image/ab67616d0000b2737e03b018aae3c7b2ec7ebfc7",
                "width": 640
            }
        },
        {
            "name": "Revelations",
            "released": "2006-08-30",
            "tracks": 13,
            "cover": {
                "height": 640,
                "url": "https://i.scdn.co/image/ab67616d0000b2734786e26a684e491124f77ee7",
                "width": 640
            }
        },
        ...
    ]
}
```

# Roadmap
- [ ] Improve tests
