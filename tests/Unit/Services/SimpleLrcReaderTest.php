<?php

namespace Tests\Unit\Services;

use App\Services\SimpleLrcReader;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Tests\TestCase;

use function Tests\test_path;

class SimpleLrcReaderTest extends TestCase
{
    private SimpleLrcReader $reader;

    public function setUp(): void
    {
        parent::setUp();

        $this->reader = new SimpleLrcReader();
    }

    public function testTryReadForMediaFile(): void
    {
        $base = sys_get_temp_dir() . '/' . Str::uuid();
        $lrcFile = $base . '.lrc';

        File::copy(test_path('blobs/simple.lrc'), $lrcFile);

        self::assertSame("Line 1\nLine 2\nLine 3", $this->reader->tryReadForMediaFile($base . '.mp3'));
        File::delete($lrcFile);
    }
}
