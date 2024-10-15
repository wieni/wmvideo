<?php

namespace Drupal\wmvideo;

class VideoEmbedder
{
    public const WM_EMBED_TYPE_YOUTUBE = 'youtube';
    public const WM_EMBED_TYPE_YOUTUBE_SHORT = 'youtube_short';
    public const WM_EMBED_TYPE_VIMEO = 'vimeo';

    public static function create($url, $autoplay = false, ?int $width = null, ?int $height = null, bool $disableKeyboard = true, ?string $title = null): ?array
    {
        [$type, $vid] = \Drupal::service('wmvideo.url_parser')->parse($url);

        if (!$type || !$vid) {
            return null;
        }

        $width = $width ?? ($type === self::WM_EMBED_TYPE_YOUTUBE_SHORT ? 360 : 640);
        $height = $height ?? ($type === self::WM_EMBED_TYPE_YOUTUBE_SHORT ? 640 : 360);

        $build = null;
        $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();

        if (\in_array($type, [self::WM_EMBED_TYPE_YOUTUBE, self::WM_EMBED_TYPE_YOUTUBE_SHORT], true)) {
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
                '#disablekb' => $disableKeyboard ? '1' : '0',
            ];

            if ($title) {
                $build['#title'] = $title;
            }
        }

        if ($type === self::WM_EMBED_TYPE_VIMEO) {
            $build = [
                '#theme' => 'wmvideo_vimeo',
                '#vid' => $vid,
                '#width' => $width,
                '#height' => $height,
                '#autoplay' => $autoplay,
                '#keyboard' => $disableKeyboard ? 'false' : 'true',
            ];

            if ($title) {
                $build['#title'] = $title;
            }
        }

        if (is_array($build)) {
            $build['#cache']['contexts'][] = 'languages:language_interface';
        }

        return $build;
    }
}
