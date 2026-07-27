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

    /** Validate the existence of a video */
    public function validate(string $url): bool
    {
        $json = $this->getInfo($url);
        return $json && !empty($json['type']);
    }

    /** Fetch metadata of a video by querying OAuth endpoints */
    public function getInfo(string $videoUrl): ?array
    {
        $url = $this->getOembedUrl($videoUrl);
        $options = [];

        if ($request = $this->requestStack->getCurrentRequest()) {
            $options[RequestOptions::HEADERS]['Referer'] = $request->getUri();
        }

        try {
            $response = $this->client->get($url, $options);
            // Guzzle's Utils::jsonDecode() is deprecated and is removed in
            // guzzlehttp/guzzle:8.0. json_decode() with JSON_THROW_ON_ERROR is
            // equivalent here: the JsonException it throws on a malformed body
            // is caught below, just like Guzzle's InvalidArgumentException was.
            $body = json_decode($response->getBody()->getContents(), true, 512, \JSON_THROW_ON_ERROR);
        } catch (\Exception $e) {
            return null;
        }

        return $body;
    }

    protected function getOembedUrl($videoUrl): ?string
    {
        [$type] = $this->urlParser->parse($videoUrl);

        if ($type === VideoEmbedder::WM_EMBED_TYPE_YOUTUBE) {
            $format = 'https://www.youtube.com/oembed?url=%s&format=json';
        } elseif ($type === VideoEmbedder::WM_EMBED_TYPE_VIMEO) {
            $format = 'https://vimeo.com/api/oembed.json?url=%s';
        } else {
            return null;
        }

        return sprintf($format, rawurlencode($videoUrl));
    }
}
