<?php

namespace Drupal\wmvideo;

class VideoEmbedder
{
    const WM_EMBED_TYPE_YOUTUBE = 'youtube';
    const WM_EMBED_TYPE_VIMEO = 'vimeo';

    public static function create(
        $url,
        $autoplay = FALSE,
        $width = 640,
        $height = 360
    ) {
        $build = NULL;

        list($type, $vid) = \Drupal::service('wmvideo.url_parser')->parse($url);

        if ($type && $vid) {
            $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();
            if ($type == self::WM_EMBED_TYPE_YOUTUBE) {
                global $base_url;
                $domain = $base_url;
                $build = array(
                    '#theme' => 'wmvideo_youtube',
                    '#vid' => $vid,
                    '#width' => $width,
                    '#height' => $height,
                    '#autoplay' => $autoplay,
                    '#lang' => $lang,
                    '#domain' => $domain,
                );
            } elseif ($type == self::WM_EMBED_TYPE_VIMEO) {
                $build = array(
                    '#theme' => 'wmvideo_vimeo',
                    '#vid' => $vid,
                    '#width' => $width,
                    '#height' => $height,
                    '#autoplay' => $autoplay,
                );
            }
        }

        return $build;
    }
}
