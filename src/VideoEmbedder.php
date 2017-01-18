<?php

namespace Drupal\wmvideo;

class VideoEmbedder {
    
    const WM_EMBED_TYPE_YOUTUBE = 'youtube';
    const WM_EMBED_TYPE_VIMEO = 'vimeo';
    
    public static function create(
        $url,
        $autoplay = FALSE,
        $width = 640,
        $height = 360
    ) {
        $build = NULL;
        
        $vid = FALSE;
        $type = FALSE;
        
        // Adapted from wm_gallery module.
        if (preg_match('/youtu\.?be/i', $url)) {
            // Stolen from YouTube Field module.
            $regexp = array(
                '/youtube\.com\/watch\?v=([a-z0-9\-_]+)/i',
                '/youtu.be\/([a-z0-9\-_]+)/i',
                '/youtube\.com\/v\/([a-z0-9\-_]+)/i',
            );
            
            foreach ($regexp as $regex) {
                if (preg_match($regex, $url, $matches)) {
                    $vid = $matches[1];
                    $type = self::WM_EMBED_TYPE_YOUTUBE;
                    break;
                }
            }
        } elseif (preg_match('/vimeo\.(.*?)\/([0-9]+)/i', $url, $match) && isset($match[2])) {
            $vid = $match[2];
            $type = self::WM_EMBED_TYPE_VIMEO;
        }
        
        if ($vid && $type) {
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
