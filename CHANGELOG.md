# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

```
## [tag] - YYYY-MM-DD
[tag]: https://github.com/eureka-framework/component-console/compare/6.1.0...master
### Changed
- Change 1
### Added
- Added 1
### Removed
- Remove 1
```

----

## [6.1.0] - 2023-11-28
[6.1.0]: https://github.com/eureka-framework/component-console/compare/6.0.1...6.1.0
### Added
- Add PHP 8.3 compatibility
- Add PHP CS fixer
### Changed
- Update Makefile
- Update version
- Update composer.json
### Removed
- Drop PHPCS support

## [6.0.1] - 2023-07-06
[6.0.1]: https://github.com/eureka-framework/component-console/compare/6.0.0...6.0.1
### Added
- Cursor: can now pass stream input in constructor
- Cursor::position(): fix reading input

## [6.0.0] - 2023-07-06
[6.0.0]: https://github.com/eureka-framework/component-console/compare/5.3.1...6.0.0
### Added
- Terminal, Cursor & Shell classes
- Options, Option & OptionsParser classes
- Add Output & Input classes
- Add lots of tests
- Add some examples
- Add lots of Color classes
### Changed
- Rework Progress
- Rework Style
- Rework Table & elements for tables
- Update Makefile
- Update composer.json (now allow PSR log from v1 to v3)
- Now require PHP 8.1 as minimum version
### Removed
- Remove IO class
- Remove Color class
- Remove Arguments & related classes

## [5.3.1] - 2023-06-14
[5.3.1]: https://github.com/eureka-framework/component-console/compare/5.3.0...5.3.1
### Added
- Re-add PHP 8.1 config
### Changed
- Update GitHub workflow
- Update Makefile
- Update composer.json (now allow PSR log from v1 to v3)

## [5.3.0] - 2023-01-03
[5.3.0]: https://github.com/eureka-framework/component-console/compare/5.2.0...5.3.0
### Added
 * Style: no more BLACK background as default
 * Some minor fix in phpdoc & types
 * Add php 8.2 compatibility check
 * Update GitHub action workflow

## [5.2.0] - 2022-12-15
[5.2.0]: https://github.com/eureka-framework/component-console/compare/5.1.0...5.2.0
### Added
 * Add Out::clear() to clear current term screen

## [5.1.0] - 2022-02-18
[5.1.0]: https://github.com/eureka-framework/component-console/compare/5.0.0...5.1.0
### Changed
 * Now compatible with PHP 7.4, 8.0 & 8.1
 * Rework Makefile
### Added
 * Add Table Unicode border style + tests
 * Add Check compatibility with PHP 7.4 & 8.1 in CI
 * Add phpstan + fix errors

## [5.0.0] - 2020-10-29
### Changed 
 * Rename Eurekon to component-console
 * Now require PHP 7.4
 * Update code
 * Upgrade phpcodesniffer to v0.7 for composer 2.0
### Added
 * Add tests
 * Add simplifications
 * Add CI


----

## [1.2.2] - 2020-02-14
### Changed
 * Exclude logged route
 
## [1.2.1] - 2019-11-23
### Changed
 * Fix breadcrumb

## [1.2.0] - 2019-09-12
### Changed
 * Menu can have secondary menu
 * Carousel: add getSubtitle method & set title as optional



## [1.1.1] - 2019-07-08
### Changed
 * Fix method name according to new http kernel version
 
## [1.1.0] - 2019-06-07
### Changed
 * Force strict type hinting
### Added
 * Add carousel classes



## [1.0.0] - 2019-04-03
### Added
  * Add Breadcrumb item & controller aware trait
  * Add Flash notification service & controller aware trait
  * Add Menu item & controller aware trait
  * Add meta controller aware trait
  * Add Notification item
