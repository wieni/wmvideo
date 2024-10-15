<?php

namespace Drupal\wmvideo\TwigExtension;

use Drupal\wmvideo\VideoEmbedder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class VideoEmbedExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'embedVideo',
                [$this, 'embedVideo'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    public function embedVideo($url, $autoplay = false, $width = 640, $height = 360, $disableKeyboard = true): ?array
    {
        return VideoEmbedder::create($url, $autoplay, $width, $height, $disableKeyboard);
    }
}
