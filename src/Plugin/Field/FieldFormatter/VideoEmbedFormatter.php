<?php

namespace Drupal\wmvideo\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\wmvideo\VideoEmbedder;

/**
 * @FieldFormatter(
 *     id = "video_embed_formatter",
 *     label = @Translation("Video Embed"),
 *     field_types = {
 *         "link"
 *     }
 * )
 */
class VideoEmbedFormatter extends FormatterBase
{
    public function viewElements(FieldItemListInterface $items, $langcode): array
    {
        $elements = [];

        foreach ($items as $delta => $item) {
            $elements[$delta] = VideoEmbedder::create($item->getUrl()
                ->getUri());
        }

        return $elements;
    }
}
