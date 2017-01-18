<?php

namespace Drupal\wmvideo\TwigExtension;

use Drupal\wmvideo\VideoEmbedder;

class VideoEmbedExtension extends \Twig_Extension
{
    
    /**
     * {@inheritdoc}
     * This function must return the name of the extension. It must be unique.
     */
    public function getName()
    {
        return 'embedVideo';
    }
    
    /**
     * In this function we can declare the extension function
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction(
                'embedVideo',
                array($this, 'embedVideo'),
                array('is_safe' => array('html'))
            ),
        );
    }
    
    public function embedVideo(
        $url,
        $autoplay = false,
        $width = 640,
        $height = 360
    ) {
        return VideoEmbedder::create($url, $autoplay, $width, $height);
    }
}
