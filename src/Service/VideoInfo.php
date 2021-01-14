<?php

namespace Drupal\wmvideo\Service;

use Drupal\wmvideo\VideoEmbedder;

class VideoInfo
{
    /** @var UrlParser */
    protected $urlParser;

    public function __construct(
        UrlParser $urlParser
    ) {
        $this->urlParser = $urlParser;
    }

    /**
     * Validate the existence of a video
     *
     * @param string $url
     * @return bool|null
     */
    public function validate($url)
    {
        $json = $this->getInfo($url);
        return $json && !empty($json['type']);
    }

    /**
     * Fetch metadata of a video by querying OAuth endpoints
     *
     * @param string $videoUrl
     * @return mixed
     */
    public function getInfo($videoUrl)
    {
        $response = @file_get_contents($this->getOembedUrl($videoUrl));
        return $response ? @json_decode($response, true) : null;
    }

    protected function getOembedUrl($videoUrl)
    {
        $format = null;
        list($type) = $this->urlParser->parse($videoUrl);

        if ($type === VideoEmbedder::WM_EMBED_TYPE_YOUTUBE) {
            $format = 'https://www.youtube.com/oembed?url=%s&format=json';
        } else if ($type === VideoEmbedder::WM_EMBED_TYPE_VIMEO) {
            $format = 'https://vimeo.com/api/oembed.json?url=%s';
        } else {
            return null;
        }

        return sprintf($format, rawurlencode($videoUrl));
    }
}
