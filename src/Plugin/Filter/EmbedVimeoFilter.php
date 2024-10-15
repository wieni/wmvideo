<?php

namespace Drupal\wmvideo\Plugin\Filter;

use Drupal\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Render\RendererInterface;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;

/**
 * @Filter(
 *     id = "filter_vimeo_embed",
 *     title = @Translation("Embed Vimeo elements"),
 *     description = @Translation("Allows you to embed Vimeo elements using [vimeo:videoid] or [vimeo:http://vimeo.com/videoid]"),
 *     type = Drupal\filter\Plugin\FilterInterface::TYPE_MARKUP_LANGUAGE,
 * )
 */
class EmbedVimeoFilter extends FilterBase
{
    /** @var RendererInterface */
    protected $renderer;

    public static function create(
        ContainerInterface $container,
        array $configuration,
        $plugin_id,
        $plugin_definition
    ) {
        $instance = new static($configuration, $plugin_id, $plugin_definition);
        $instance->renderer = $container->get('renderer');

        return $instance;
    }

    public function process($text, $langcode): FilterProcessResult
    {
        $new_text = preg_replace_callback("|\[vimeo:([^]]*)\]|i", 'self::replace', $text);
        return new FilterProcessResult($new_text);
    }

    public function replace($results)
    {
        $vid = strip_tags($results[1]);
        if (preg_match('/vimeo\.(.*?)\/([0-9]+)/i', $vid, $match) && isset($match[2])) {
            $vid = $match[2];
        }

        $width = 640;
        $height = 360;

        $build = [
            '#theme' => 'wmvideo_vimeo',
            '#vid' => $vid,
            '#width' => $width,
            '#height' => $height,
            '#keyboard' => 'false',
        ];

        return $this->renderer->render($build);
    }
}
