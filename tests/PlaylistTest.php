<?php

use App\Playlist;

class PlaylistTest extends TestCase
{
    public function testRequiresPlaylist()
    {
        $this->json('POST', 'api/playlists')
            ->seeStatusCode(422)
            ->seeJson([
                'name' => ['The name field is required.']
            ]);
    }

    public function testStoreNewPlaylist()
    {
        $payload = ['name' => 'new'];

        $this->json('POST', 'api/playlists', $payload)
            ->seeJsonStructure([
                'name'
            ])
            ->seeStatusCode(201);
    }

    public function testRetrievePlaylist()
    {
        factory(Playlist::class)->create([
            'name' => 'Test'
        ]);

        $this->json('GET', '/api/playlists')
            ->seeStatusCode(200)
            ->seeJsonContains(
                ['name' => 'Test']
            )
            ->seeJsonStructure([
                'data' => ['*' => ['name']],
            ]);
    }

    public function testDeletePlaylist()
    {
        $playlist = factory(Playlist::class)->create([
            'name' => 'Test'
        ]);

        $this->json('DELETE', '/api/playlists/' . $playlist->id, [], [])
            ->seeStatusCode(204);
    }
}
