# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/). At least so much as we feel like it.

## [Unreleased] - [unreleased]
### Added
- **Dependencies:** 
  - riimu/kit-baseconversion: v1.*
  - ircmaxell/random-lib: v1.1.*
  - php-ds/php-ds: v1.1.*
- **Suggested Extensions**
  - ext-ds: *
  - ext-stats: *
- **Dev Dependencies:**
  - phpunit/phpunit: v4.8.*
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
- Shapes:
  - Base:
    - Line
    - TwoDimensionNonCurved
- Providers:
  - Stats:
    - Distributions:
      - Normal
      - Poisson
    - Stats
  - BCProvider
  - SequenceProvider
  - SeriesProvider
  - TrigonometryProvider