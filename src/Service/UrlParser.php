<?php

namespace Drupal\wmvideo\Service;

use Drupal\wmvideo\VideoEmbedder;
use GuzzleHttp\Psr7\Query;

class UrlParser
{
    protected const DOMAINS = [
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

    public function parse($url): array
    {
        $type = null;
        $vid = null;
        $url = parse_url($url);

        if (!$url || !isset($url['host'])) {
            return [$type, $vid];
        }

        if (isset($url['query'])) {
            $url['query'] = Query::parse($url['query']);
        }

        if (in_array($url['host'], self::DOMAINS[VideoEmbedder::WM_EMBED_TYPE_YOUTUBE], true)) {
            $type = VideoEmbedder::WM_EMBED_TYPE_YOUTUBE;

            if (isset($url['path']) && $url['host'] === 'youtu.be') {
                $vid = trim($url['path'], '/');
            } elseif (isset($url['path']) && strpos($url['path'], '/embed') !== false) {
                $vid = str_replace('/embed/', '', $url['path']);
            } elseif (isset($url['path']) && strpos($url['path'], '/shorts') !== false) {
                $vid = str_replace('/shorts/', '', $url['path']);
                $type = VideoEmbedder::WM_EMBED_TYPE_YOUTUBE_SHORT;
            } elseif (isset($url['query']['v'])) {
                $vid = $url['query']['v'];
            }
        }

        if (isset($url['path']) && in_array($url['host'], self::DOMAINS[VideoEmbedder::WM_EMBED_TYPE_VIMEO], true)) {
            $type = VideoEmbedder::WM_EMBED_TYPE_VIMEO;

            if (preg_match('#^/video/(?<vid>.+)#', $url['path'], $matches)) {
                $vid = $matches['vid'];
            } elseif (preg_match('#^/(?<vid>.+)/.+$#', $url['path'], $matches)) {
                $vid = $matches['vid'];
            } else {
                $vid = trim($url['path'], '/');
            }
        }

        return [$type, $vid];
    }
}
