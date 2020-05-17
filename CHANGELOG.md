# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/). At least so much as we feel like it.

## [Unreleased]
### Added

- Providers:
  - Distributions:
    - Base: Distribution abstract class
  - ArithmeticProvider:
    - Added a scale argument to all methods that were missing it.
  - PolyfillProvider
  - SequenceProvider:
    - `nthFibonacciNumber` function
  - StatsProvider:
    - Added docblocks for all methods
    - Added `gammaFunction` function
- Types:
  - Base:
    - Interfaces:
        - BaseConversionInterface
        - ComplexInterface
        - DecimalInterface:
          - Added hyperbolic trig functions
          - Added `exp()`
        - NumberInterface:
          - Added `isComplex(): bool` method
        - MatrixInterface
        - NumberCollectionInterface
    - Number:
      - numberOfTotalDigits()
      - numberOfIntDigits()
      - numberOfDecimalDigits()
      - numberOfSigDecimalDigits()
      - isComplex()
      - asComplex()
  - Traits:
    - ArithmeticTrait
    - ComparisonTrait
    - IntegerMathTrait
    - InverseTrigonometryTrait
    - LogTrait
    - ScaleTrait
    - TrigonometryTrait:
      - Added implementation of hyperbolic trig functions
  - ComplexNumber
  - Coordinate
  - Decimal
  - Expression
  - Matrix
  - NumberCollection
- Values:
  - Algebra
    - PolynomialFunction
  - Geometry
    - CoordinateSystems
      - CartesianCoordinate
      - CylindricalCoordinate
      - PolarCoordinate
      - SphericalCoordinate
  - ImmutableComplexNumber
  - ImmutableFraction:
    - Added implementation of `isComplex(): bool`
  - ImmutableMatrix
  - ImmutableNumber:
    - Added implementation of `isComplex(): bool`
  - MutableComplexNumber
  - MutableFraction:
    - Added implementation of `isComplex(): bool`
  - MutableMatrix
  - MutableNumber:
    - Added implementation of `isComplex(): bool`
- Factories:
  - Collections
  - ComplexNumbers
  - Matrices

### Removed

- Composer: 
  - **Suggested:**
    - ext-stats: * (As this library is no longer maintained and unavailable on PHP 7+, it is being removed)
- Providers:
  - SequenceProvider
    - Removed empty `nthSecTanCoefNumber` function until an implementation exists

### Changed

- Providers:
  - Distributions:
    - Removed references to ext-stats in all distributions
    - Fixed missing @throws in docblocks in all distributions
    - Now extend `Distribution` abstract class
    - Use the randomInt() method in PolyfillProvider instead of RandomLib directly
  - SequenceProvider:
    - Allowed `nthEvenNumber()`, `nthOddNumber()`, `nthPowerNegativeOne()`, `nthEulerZigzag()`, and `nthFibonacciNumber()` to return windows of the sequence as a NumberCollection object
- Types:
  - Base:
    - Interfaces:
      - ALL: Changed namespace from `Samsara\Fermat\Types\Base` to `Samsara\Fermat\Types\Base\Interfaces`
      - DecimalInterface:
        - Changed signature of `log10()` and `ln()` to `$scale = null` instead of `$scale = 10`
        - Changed type of `$scale` parameter in `log10()` and `ln()` from `int` to `int|null`
      - NumberInterface:
        - Moved the `convertToBase()` method to the new `BaseConversionInterface`
    - Traits:
      - ArithmeticTrait:
        - Updated all arithmetic functions to work with imaginary numbers, complex numbers, and negative numbers (square root)
    - Number:
      - Changed namespace from `Samsara\Fermat\Types` to `Samsara\Fermat\Types\Base`
      - Moved many methods into traits
      - Now passes the scale setting to ALL calls to `ArithmeticProvider` allowing scale up to 2147483646 digits.
      - Trig functions are now arbitrary scale
      - numberOfLeadingZeros() now returns `int` type, as was originally intended
  - Fraction:
    - Moved many methods into traits
  

### Fixed

- Providers:
  - Distributions:
    - Exponential:
      - Fixed accidental cast to float in `random` function
  - SequenceProvider:
    - Fixed erroneous limit on Euler Zigzag sequence index (45 -> 50) and updated exception to reflect current limit (39 -> 50)
- Types:
  - Traits:
    - LogTrait:
      - Fixed `$scale` to work the way it does in other functions for `log10()` and `ln()`
  - Tuple:
    - Changed the constructor so that it properly works with either parameter collection or a single array
  - ImmutableNumber:
    - Fixed the problem with scale in continuousModulo()
  - MutableNumber:
    - Fixed the problem with scale in continuousModulo()

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
- Values:
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

[Unreleased]: https://github.com/JordanRL/Fermat/compare/v1.0.2...HEAD
[1.0.2]: https://github.com/JordanRL/Fermat/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/JordanRL/Fermat/compare/v1.0.0...v1.0.1
