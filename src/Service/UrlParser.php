<?php

namespace Drupal\wmvideo\Service;

use Drupal\wmvideo\VideoEmbedder;

class UrlParser
{
    public function parse($url)
    {
        $type = null;
        $vid = null;

        // Adapted from wm_gallery module.
        if (preg_match('/youtu\.?be/i', $url)) {
            // Stolen from YouTube Field module.
            $regexp = array(
                '/youtube\.com\/watch\?v=([a-z0-9\-_]+)/i',
                '/youtu.be\/([a-z0-9\-_]+)/i',
                '/youtube\.com\/v\/([a-z0-9\-_]+)/i',
            );

            foreach ($regexp as $regex) {
                if (preg_match($regex, $url, $matches)) {
                    $vid = $matches[1];
                    $type = VideoEmbedder::WM_EMBED_TYPE_YOUTUBE;
                    break;
                }
            }
        } elseif (preg_match('/vimeo\.(.*?)\/([0-9]+)/i', $url, $match) && isset($match[2])) {
            $vid = $match[2];
            $type = VideoEmbedder::WM_EMBED_TYPE_VIMEO;
        }

        return [$type, $vid];
    }
}
