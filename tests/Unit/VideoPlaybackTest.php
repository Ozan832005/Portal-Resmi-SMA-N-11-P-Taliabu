<?php

namespace Tests\Unit;

use App\Models\Video;
use PHPUnit\Framework\TestCase;

class VideoPlaybackTest extends TestCase
{
    public function test_youtube_watch_links_are_converted_to_embedded_player_urls(): void
    {
        $video = new Video;
        $video->url = 'https://www.youtube.com/watch?v=abcDEF12345&t=15';

        $this->assertSame([
            'type' => 'youtube',
            'url' => 'https://www.youtube.com/embed/abcDEF12345?rel=0',
        ], $video->playback());
    }

    public function test_supported_direct_video_files_can_be_played_inline(): void
    {
        $video = new Video;
        $video->url = 'https://cdn.example.test/school-video.mp4?token=sample';

        $this->assertSame([
            'type' => 'file',
            'url' => 'https://cdn.example.test/school-video.mp4?token=sample',
        ], $video->playback());
    }

    public function test_untrusted_embed_hosts_are_not_embedded(): void
    {
        $video = new Video;
        $video->url = 'https://youtube.com.example.test/watch?v=abcDEF12345';

        $this->assertNull($video->playback());
    }
}
