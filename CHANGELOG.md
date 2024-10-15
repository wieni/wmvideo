# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.5.0] - 2024-10-15
### Added
- Support GuzzleHttp versions below 8.0
- Support Drupal ^9.3
- `embedVideo` twig function now allows adding `title` attribute to iframe tag
- `embedVideo` twig function now allows you to disable keyboard to prevent interference with help technology
    - Add `keyboard` query parameter to vimeo embed URL
    - Add `disablekb` attribute to YouTube iframe

## [1.4.0] - 2024-03-25
### Added
- Support YouTube Shorts

## [1.3.1] - 2024-03-19
### Fixed
- Fix Drupal deprecations
### Changed
- Bump PHP version to `8.1`

## [1.2.5] - 2021-08-30
### Added
- Add issue & pull request templates
- Add coding standard fixers & gitignore file
- Add README & CHANGELOG

### Changed
- Add PHP 7.1 dependency
- Add `link` & `filter` module dependencies
- Fix code style, deprecations

### Fixed
- Add cache metadata to render array in `VideoEmbedder`
