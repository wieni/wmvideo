<?php

namespace Drupal\wmvideo;

class VideoEmbedder
{
    public const WM_EMBED_TYPE_YOUTUBE = 'youtube';
    public const WM_EMBED_TYPE_VIMEO = 'vimeo';

    public static function create($url, $autoplay = false, $width = 640, $height = 360): ?array
    {
        [$type, $vid] = \Drupal::service('wmvideo.url_parser')->parse($url);

        if (!$type || !$vid) {
            return null;
        }

        $build = null;
        $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();

        if ($type === self::WM_EMBED_TYPE_YOUTUBE) {
            global $base_url;
            $domain = $base_url;
            $build = [
                '#theme' => 'wmvideo_youtube',
                '#vid' => $vid,
                '#width' => $width,
                '#height' => $height,
                '#autoplay' => $autoplay,
                '#lang' => $lang,
                '#domain' => $domain,
            ];
        }

        if ($type === self::WM_EMBED_TYPE_VIMEO) {
            $build = [
                '#theme' => 'wmvideo_vimeo',
                '#vid' => $vid,
                '#width' => $width,
                '#height' => $height,
                '#autoplay' => $autoplay,
            ];
        }

        if (is_array($build)) {
            $build['#cache']['contexts'][] = 'languages:language_interface';
        }

        return $build;
    }
}
