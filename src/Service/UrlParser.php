<?php

namespace Drupal\wmvideo\Service;

use Drupal\wmvideo\VideoEmbedder;
use function GuzzleHttp\Psr7\parse_query;

class UrlParser
{
    const DOMAINS = [
        VideoEmbedder::WM_EMBED_TYPE_YOUTUBE => [
            'www.youtube.com',
            'youtube.com',
            'youtu.be',
        ],
        VideoEmbedder::WM_EMBED_TYPE_VIMEO => [
            'www.vimeo.com',
            'vimeo.com',
        ],
    ];

    public function parse($url)
    {
        $type = null;
        $vid = null;

        if (!$url = parse_url($url)) {
            return [$type, $vid];
        }

        if (isset($url['query'])) {
            $url['query'] = parse_query($url['query']);
        }

        if (in_array($url['host'], self::DOMAINS[VideoEmbedder::WM_EMBED_TYPE_YOUTUBE], true)) {
            $type = VideoEmbedder::WM_EMBED_TYPE_YOUTUBE;

            if ($url['host'] === 'youtu.be') {
                $vid = trim($url['path'], '/');
            } elseif (strpos($url['path'], '/embed') !== false) {
                $vid = str_replace('/embed/', '', $url['path']);
            } elseif (isset($url['query']['v'])) {
                $vid = $url['query']['v'];
            }
        }

        if (in_array($url['host'], self::DOMAINS[VideoEmbedder::WM_EMBED_TYPE_VIMEO], true)) {
            $type = VideoEmbedder::WM_EMBED_TYPE_VIMEO;
            $vid = trim($url['path'], '/');
        }

        return [$type, $vid];
    }
}
