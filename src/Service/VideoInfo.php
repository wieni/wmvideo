<?php

namespace Drupal\wmvideo\Service;

use Drupal\wmvideo\VideoEmbedder;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Symfony\Component\HttpFoundation\RequestStack;

class VideoInfo
{
    /** @var RequestStack */
    protected $requestStack;
    /** @var Client */
    protected $client;
    /** @var UrlParser */
    protected $urlParser;

    public function __construct(
        RequestStack $requestStack,
        Client $client,
        UrlParser $urlParser
    ) {
        $this->requestStack = $requestStack;
        $this->client = $client;
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
        $url = $this->getOembedUrl($videoUrl);
        $options = [];

        if ($request = $this->requestStack->getCurrentRequest()) {
            $options[RequestOptions::HEADERS]['Referer'] = $request->getUri();
        }

        try {
            $response = $this->client->get($url, $options);
            $body = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return null;
        }

        return $body;
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
