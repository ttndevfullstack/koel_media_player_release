<?php

namespace Tests\Feature;

use App\Models\Artist;
use App\Services\MediaInformationService;
use App\Values\ArtistInformation;
use Mockery;
use Tests\TestCase;

class ArtistInformationTest extends TestCase
{
    public function testGet(): void
    {
        config(['koel.lastfm.key' => 'foo']);
        config(['koel.lastfm.secret' => 'geheim']);

        $artist = Artist::factory()->create();

        $lastfm = self::mock(MediaInformationService::class);
        $lastfm->shouldReceive('getArtistInformation')
            ->with(Mockery::on(static fn (Artist $a) => $a->is($artist)))
            ->andReturn(ArtistInformation::make(
                url: 'https://lastfm.com/artist/foo',
                image: 'https://lastfm.com/image/foo',
                bio: [
                    'summary' => 'foo',
                    'full' => 'bar',
                ],
            ));

        $this->getAs('api/artists/' . $artist->id . '/information')
            ->assertJsonStructure(ArtistInformation::JSON_STRUCTURE);
    }

    public function testGetWithoutLastfmStillReturnsValidStructure(): void
    {
        config(['koel.lastfm.key' => null]);
        config(['koel.lastfm.secret' => null]);

        $this->getAs('api/artists/' . Artist::factory()->create()->id . '/information')
            ->assertJsonStructure(ArtistInformation::JSON_STRUCTURE);
    }
}
