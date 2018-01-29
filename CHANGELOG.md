# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/). At least so much as we feel like it.

## [Unreleased] - [unreleased]
### Added

- Providers:
  - CalculusProvider
  - ArithmeticProvider
    - Added a precision argument to all methods that were missing it.
  - StatsProvider
    - Added docblocks for all methods
    - Added gamma function
- Types:
  - Interfaces:
    - MatrixInterface
    - NumberCollectionInterface
  - NumberCollection

### Removed

- Composer 
  - **Suggested:**
    - ext-stats: * (As this library is no longer maintained an unavailable on PHP 7+, it is being removed)

### Changed

- Providers:
  - Distributions:
    - Removed references to ext-stats in all distributions
    - Fixed missing @throws in docblocks in all distributions
- Types:
  - Number
    - Now passes the precision setting to ALL calls to ArithmeticProvider allowing precision up to 2147483646 digits. (Still limited by constants on log and trig functions.)

### Fixed

- Providers:
  - SequenceProvider
    - Fixed erroneous limit on Euler Zigzag sequence index (45 -> 50) and updated exception to reflect current limit (39 -> 50)

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
