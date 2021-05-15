<?php

use App\Album;
use App\Genre;
use App\Mediatype;
use App\Track;

class TrackTest extends TestCase
{
    public function testRequiresTrack()
    {
        $this->json('POST', 'api/tracks')
            ->seeStatusCode(422)
            ->seeJson([
                'name' => ['The name field is required.'],
                'milliseconds' => ['The milliseconds field is required.'],
                'bytes' => ['The bytes field is required.'],
                'unit_price' => ['The unit price field is required.']
            ]);
    }

    public function testStoreNewTrack()
    {
        $mediatype = factory(Mediatype::class)->create();
        $genre = factory(Genre::class)->create();
        $album = factory(Album::class)->create();

        $payload = ['name' => 'new',
            'album_id' => $album->id,
            'mediatype_id' => $mediatype->id,
            'genre_id' => $genre->id,
            'milliseconds' => 325,
            'bytes' => 123,
            'unit_price' => 0.99];

        $this->json('POST', 'api/tracks', $payload)
            ->seeStatusCode(201)
            ->seeJsonStructure([
                'name'
            ]);
    }

    public function testRetrieveTrack()
    {
        factory(Track::class)->create([
            'name' => 'Test'
        ]);

        $this->json('GET', '/api/tracks')
            ->seeStatusCode(200)
            ->seeJsonContains(
                ['name' => 'Test']
            )
            ->seeJsonStructure([
                'data' => ['*' => ['name']],
            ]);
    }

    public function testDeleteTrack()
    {
        $track = factory(Track::class)->create();

        $this->json('DELETE', '/api/tracks/' . $track->id, [], [])
            ->seeStatusCode(204);
    }
}
