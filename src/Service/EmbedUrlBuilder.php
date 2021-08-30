<?php

namespace Drupal\wmvideo\Service;

use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\wmvideo\VideoEmbedder;

class EmbedUrlBuilder
{
    /** @var LanguageManagerInterface */
    protected $languageManager;

    public function __construct(
        LanguageManagerInterface $languageManager
    ) {
        $this->languageManager = $languageManager;
    }

    public function build($type, $vid, $options = []): ?string
    {
        global $base_url;
        $langcode = $this->languageManager->getCurrentLanguage()->getId();

        if ($type === VideoEmbedder::WM_EMBED_TYPE_YOUTUBE) {
            $format = 'https://www.youtube.com/embed/%s?%s';
            $query = [
                'origin' => $base_url,
                'color' => 'white',
                'autohide' => 1,
                'rel' => 0,
                'showinfo' => 0,
                'hl' => $langcode,
                'wmode' => 'opaque',
                'autoplay' => 0,
            ];
        } elseif ($type === VideoEmbedder::WM_EMBED_TYPE_VIMEO) {
            $format = 'https://player.vimeo.com/video/%s?%s';
            $query = [
                'portrait' => 0,
                'badge' => 0,
                'byline' => 0,
                'title' => 0,
                'color' => 'fff',
                'autoplay' => 0,
            ];
        } else {
            return null;
        }

        return sprintf($format, $vid, http_build_query($query + $options));
    }
}
