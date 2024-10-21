<?php

namespace Tests\Feature;

use App\Models\Song;
use Tests\TestCase;

use function Tests\create_admin;

class SongVisibilityTest extends TestCase
{
    public function testChangingVisibilityIsForbiddenInCommunityEdition(): void
    {
        $owner = create_admin();
        Song::factory(3)->create();

        $this->putAs('api/songs/publicize', ['songs' => Song::query()->pluck('id')->all()], $owner)
            ->assertForbidden();

        $this->putAs('api/songs/privatize', ['songs' => Song::query()->pluck('id')->all()], $owner)
            ->assertForbidden();
    }
}
