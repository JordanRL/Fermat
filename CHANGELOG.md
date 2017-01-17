# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/). At least so much as we feel like it.

## [Unreleased] - [unreleased]
### Added

*Nothing*

### Removed

*Nothing*

### Changed

- ArithmeticProvider
  - Added a precision argument to all methods that were missing it.
- Number
  - Now passes the precision setting to ALL calls to ArithmeticProvider allowing precision up to 2147483646 digits. (Still limited by constants on log and trig functions.)

### Fixed

*Nothing*

## [1.0.2] - 2017-01-16
### Fixed
- DecimalInterface
  - Added the following to correctly reflect usage:
    - tan()
    - cot()
    - sec()
    - csc()
    - arcsin()
    - arccos()
    - arctan()
    - arccot()
    - arcsec()
    - arccsc()

## [1.0.1] - 2017-01-16
### Changed
- Composer
  - **Dependencies:**
    - samsara/common: dev-master -> 1.*

## 1.0.0 - 2017-01-15
### Added
- Composer
  - **Dependencies:**
    - riimu/kit-baseconversion: v1.*
    - ircmaxell/random-lib: v1.1.*
    - php-ds/php-ds: v1.1.*
    - samsara/common: dev-master
    - ext-bcmath: *
  - **Dev Dependencies:**
    - phpunit/phpunit: v5.7.*
  - **Suggested:**
    - ext-ds: *
    - ext-stats: * (NOTE: This extension can't be used because it is not available for PHP 7+)
    - ext-gmp: *
- Types:
  - Interfaces:
    - CoordinateInterface
    - DecimalInterface
    - FractionInterface
    - NumberInterface
  - Fraction
  - Number
  - Tuple
- Numbers:
  - CartesianCoordinate
  - Currency
  - ImmutableFraction
  - ImmutableNumber
  - MutableFraction
  - MutableNumber
- Providers:
  - Distributions:
    - Exponential
    - Normal
    - Poisson
  - ArithmeticProvider
  - SequenceProvider
  - SeriesProvider
  - StatsProvider
  - TrigonometryProvider

[unreleased]: https://github.com/JordanRL/Fermat/compare/v1.0.2...HEAD
[1.0.2]: https://github.com/JordanRL/Fermat/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/JordanRL/Fermat/compare/v1.0.0...v1.0.1