<?php

namespace Drupal\wmvideo\Plugin\Filter;

use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;

/**
 * @Filter(
 *     id = "filter_youtube_embed",
 *     title = @Translation("Embed YouTube elements"),
 *     description = @Translation("Allows you to embed YouTube elements using [youtube:videoid]
 *      or [youtube:http://youtube.com/watch?v=videoid]."),
 *     type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE,
 * )
 */
class EmbedYouTubeFilter extends FilterBase
{
    public function process($text, $langcode): FilterProcessResult
    {
        $new_text = preg_replace_callback("|\[youtube:([^]]*)\]|i", 'self::replace', $text);
        return new FilterProcessResult($new_text);
    }

    public function replace($results)
    {
        $vid = strip_tags($results[1]);
        $regexp = [
            '/youtube\.com\/watch\?v=([a-z0-9\-_]+)/i',
            '/youtu.be\/([a-z0-9\-_]+)/i',
            '/youtube\.com\/v\/([a-z0-9\-_]+)/i',
        ];

        foreach ($regexp as $regex) {
            if (preg_match($regex, $vid, $matches)) {
                $vid = $matches[1];
                break;
            }
        }

        $width = 640;
        $height = 360;

        $lang = \Drupal::languageManager()->getCurrentLanguage()->getId();
        global $base_url;
        $domain = $base_url;

        $build = [
            '#theme' => 'wmvideo_youtube',
            '#vid' => $vid,
            '#width' => $width,
            '#height' => $height,
            '#lang' => $lang,
            '#domain' => $domain,
        ];

        return render($build);
    }
}
