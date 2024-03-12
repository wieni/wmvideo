<?php

namespace Drupal\wmvideo\Service;

use Drupal\wmvideo\VideoEmbedder;
use GuzzleHttp\Client;
use GuzzleHttp\Utils;
use function GuzzleHttp\json_decode;
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
            $body = Utils::jsonDecode($response->getBody()->getContents(), true);
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
