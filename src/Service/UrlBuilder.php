<?php

namespace Drupal\wmvideo\Service;

use Drupal\wmvideo\VideoEmbedder;

class UrlBuilder
{
    public function build($type, $vid)
    {
        if ($type === VideoEmbedder::WM_EMBED_TYPE_YOUTUBE) {
            $format = 'https://www.youtube.com/watch?v=%s';
        } else if ($type === VideoEmbedder::WM_EMBED_TYPE_VIMEO) {
            $format = 'https://vimeo.com/%s';
        } else {
            return null;
        }

        return sprintf($format, $vid);
    }
}
