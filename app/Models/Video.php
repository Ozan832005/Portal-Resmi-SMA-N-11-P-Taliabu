<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $table = 'videos';

    /**
     * @return array{type: 'youtube'|'file', url: string}|null
     */
    public function playback(): ?array
    {
        $url = trim((string) $this->url);
        $parts = parse_url($url);

        if (! is_array($parts) || ! in_array($parts['scheme'] ?? null, ['http', 'https'], true)) {
            return null;
        }

        $host = strtolower($parts['host'] ?? '');
        $path = trim($parts['path'] ?? '', '/');

        if (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtu.be', 'www.youtu.be', 'youtube-nocookie.com', 'www.youtube-nocookie.com'], true)) {
            $videoId = null;

            if (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
                $videoId = explode('/', $path)[0] ?? null;
            } elseif ($path === 'watch') {
                parse_str($parts['query'] ?? '', $query);
                $videoId = $query['v'] ?? null;
            } elseif (preg_match('#^(?:embed|shorts|live)/([A-Za-z0-9_-]{11})$#', $path, $matches) === 1) {
                $videoId = $matches[1];
            }

            if (is_string($videoId) && preg_match('/^[A-Za-z0-9_-]{11}$/', $videoId) === 1) {
                return [
                    'type' => 'youtube',
                    'url' => 'https://www.youtube.com/embed/'.$videoId.'?rel=0',
                ];
            }
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if (in_array($extension, ['mp4', 'webm', 'ogg'], true)) {
            return [
                'type' => 'file',
                'url' => $url,
            ];
        }

        return null;
    }
}
