Wieni Video
======================

[![Latest Stable Version](https://poser.pugx.org/wieni/wmvideo/v/stable)](https://packagist.org/packages/wieni/wmvideo)
[![Total Downloads](https://poser.pugx.org/wieni/wmvideo/downloads)](https://packagist.org/packages/wieni/wmvideo)
[![License](https://poser.pugx.org/wieni/wmvideo/license)](https://packagist.org/packages/wieni/wmvideo)

> Converting a YouTube or Vimeo URL to an embedded player

## Installation

This package requires PHP 7.1 and Drupal 8 or higher. It can be
installed using Composer:

```bash
 composer require wieni/wmvideo
```

## Usage in twig
example:

```twig
{# parameters: $url, $autoplay = false, $width = 640, $height = 360, $disableKeyboard = true, $title = null #}
{{ embedVideo(video.getLink(), false, 640, 360, true, video.getTitle()) }}
```

## Changelog
All notable changes to this project will be documented in the
[CHANGELOG](CHANGELOG.md) file.

## Security
If you discover any security-related issues, please email
[security@wieni.be](mailto:security@wieni.be) instead of using the issue
tracker.

## License
Distributed under the MIT License. See the [LICENSE](LICENSE) file
for more information.
