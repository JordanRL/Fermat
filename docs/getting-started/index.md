Fermat has some vocabulary and concepts that are unique to this library, and they are documented here.

## Providers

A provider in Fermat is a static class which provides a specific functionality to the entire library. It makes this interface available using static methods and wherever possible is permissive about the values it accepts as arguments. What is meant by that is you can, in most cases, pass an implementation of NumberInterface, a numeric string, an int, or a float. Please note that there are exceptions to this general principle and consult the method documentation.

The current list of providers, documented in more detail in the section for Providers, is:

- ArithmeticProvider
- ConstantProvider
- PolyfillProvider
- SequenceProvider
- SeriesProvider
- StatsProvider
- TrigonometryProvider

## Types

A type in Fermat is an implementation of a class of number or math concept. These are (with the exception of Tuple and NumberCollection) abstract classes that are meant to be extended into classes which can be instantiated. This is mostly to provide both mutable and immutable versions of each type. Tuples are not treated in this way as a Tuple is inherently immutable.

The current list of types is:

- ComplexNumber
- Coordinate
- Decimal
- Expression
- Fraction
- Matrix
- NumberCollection
- Tuple

## Values

A value in Fermat is a usable implementation that can be directly worked with to perform math operations of some kind. These represent concrete concepts or types of values that have specific intended behavior and usage.

The current list of values is:

- Algebra
  - PolynomialFunction
- Geometry
  - CoordinateSystems
    - CartesianCoordinate
    - CylindricalCoordinate
    - PolarCoordinate
    - SphericalCoordinate
- ImmutableComplexNumber
- ImmutableDecimal
- ImmutableFraction
- ImmutableMatrix
- MutableComplexNumber
- MutableDecimal
- MutableFraction
- MutableMatrix

## Values

There are two main ways of using this library: through direct instantiation and through the `Samsara\Fermat\Numbers` factory class.

### Using the Factory

The `Samsara\Fermat\Numbers` factory class provides a way to use the Value classes in Fermat without being as specific as those classes may require. Consider the following code:

```php
<?php

use Samsara\Fermat\Numbers;

$five = Numbers::make(Numbers::IMMUTABLE, 5);
$ten = Numbers::make(Numbers::IMMUTABLE, '10');

echo $five->add($ten); // Prints: "15"
```

Note that the `make()` method allows you to provide both an int and a string as the value. In fact, it also allows you to provide a float. The first argument is the specific class that will be used for the value, the second argument is the value itself. The third and fourth arguments are optional and represent the precision (in number of decimal places) and the base of the number respectively. The precision and base arguments will only accept integer values.

If you do not specify a precision value, and you are using the default values, it automatically has a precision of either 10, or the string length of the input value, whichever is greater.

Here is an example of using the factory method to make a value that is in a base other than base10:

```php
<?php

use Samsara\Fermat\Numbers;

$five = Numbers::make(Numbers::IMMUTABLE, '10', null, 5); // Value in base5
$ten = Numbers::make(Numbers::IMMUTABLE, '10'); // Value in base10

echo $ten->add($five); // Prints: "15" (The sum in base10)
echo $five->add($ten); // Prints: "30" (The sum in base5)
```

You can convert the base of a number freely:

```php
<?php

use Samsara\Fermat\Numbers;

$five = Numbers::make(Numbers::IMMUTABLE, '10', null, 5); // Value in base5
$ten = Numbers::make(Numbers::IMMUTABLE, '10'); // Value in base10

$fifteen = $five->add($ten);

echo $fifteen; // Prints: "30" (The sum in base5)
echo $fifteen->convertToBase(10); // Prints: "15" (The sum in base10)
echo $fifteen->convertToBase(16); // Prints: "F" (The sum in base16)
```

You can also pass strings, integers, and floats directly to the arithmetic methods instead of instances of Values, but whenever you do this it will always be assumed that the number being passed as an argument is in base10:

```php
<?php

use Samsara\Fermat\Numbers;

$five = Numbers::make(Numbers::IMMUTABLE, '10', null, 5); // Value in base5

echo $five->add(10)->convertToBase(10); // Prints: "15" (The sum in base10)
```

Sometimes you will have a variable that *might* be an instance of a Value, or might be a string/int/float, and you're not sure which. If you want to ensure that it has a specific class, you can use the `makeOrDont()` method. This is especially useful if you want to change a Mutable Value to an Immutable Value without affecting the original instance:

```php
<?php

use Samsara\Fermat\Numbers;

$fiveMutable   = Numbers::make(Numbers::MUTABLE, 5);
$fiveImmutable = Numbers::make(Numbers::IMMUTABLE, 5);
$fiveString    = '5';
$fiveInt       = 5;

$first  = Numbers::makeOrDont(Numbers::IMMUTABLE, $fiveMutable);
$second = Numbers::makeOrDont(Numbers::IMMUTABLE, $fiveImmutable);
$third  = Numbers::makeOrDont(Numbers::IMMUTABLE, $fiveString);
$fourth = Numbers::makeOrDont(Numbers::IMMUTABLE, $fiveInt);

echo get_class($first);  // Prints: "Samsara\Fermat\Values\ImmutableNumber"
echo get_class($second); // Prints: "Samsara\Fermat\Values\ImmutableNumber"
echo get_class($third);  // Prints: "Samsara\Fermat\Values\ImmutableNumber"
echo get_class($fourth); // Prints: "Samsara\Fermat\Values\ImmutableNumber"
```

This allows you to pass in any implementation of `NumberInterface` and get an object matching your desired Value. It also allows you to pass in an array of values, of any acceptable types in any combination, and get back an array with matching keys where the values are all of your desired Value.

The factory class also contains helper methods for several common math constants. These methods are:

- `makePi($precision = null)`: The pi constant
- `make2Pi($precision = null)`: The pi constant multiplied by 2 (also known as tau)
- `makeTau($precision = null)`: The tau constant (pi multiplied by 2)
- `makeE($precision = null)`: Euler's Number
- `makeGoldenRatio($precision = null)`: The golden ratio
- `makeNaturalLog10($precision = null)`: The natural log of 10
- `makeOne($precision = null)`: The number 1
- `makeZero($precision = null)`: The number 0

All of these are returned as instances of `ImmutableNumber` with a default precision of 100. These constants are also available as strings on the `Numbers` class using the following constants:

- `Numbers::PI`
- `Numbers::TAU`
- `Numbers::E`
- `Numbers::GOLDEN_RATIO`
- `Numbers::LN_10`

The fully qualified class names for the built in values, as strings, are available as constants also, which is what has been demonstrated so far in these examples:

- `Numbers::IMMUTABLE`: ImmutableNumber
- `Numbers::MUTABLE`: MutableNumber
- `Numbers::IMMUTABLE_FRACTION`: ImmutableFraction
- `Numbers::MUTABLE_FRACTION`: MutableFraction
- `Numbers::CARTESIAN_COORDINATE`: CartesianCoordinate

### Direct Instantiation

You can also directly instantiate the Value classes if you wish, and sometimes it is desirable to do so.

#### ImmutableNumber and MutableNumber

These classes extend the `Samsara\Fermat\Types\Decimal` abstract class, and their constructors have the following signature:

`__construct(int|float|numeric $value, $precision = 10, $base = 10)`

**These classes implement the `SimpleNumberInterface` and `DecimalInterface`**

#### ImmutableFraction and MutableFraction

These classes extend the `Samsara\Fermat\Types\Fraction` abstract class, and their constructors have the following signature:

`__construct(int|float|numeric|DecimalInterface $numerator, int|float|numeric|DecimalInterface $denominator, $base = 10)`

Note that if either $numerator or $denominator are not whole numbers, they will be rounded. Both the numerator and denominator are stored internally by `Fraction` as instances of `ImmutableNumber`, and they will be coerced into this Value.

**These classes implement the `SimpleNumberInterface` and `FractionInterface`**

#### CartesianCoordinate

This class extends the `Samsara\Fermat\Types\Coordinate` class, and its constructor has the following signature:

`__construct(array $data)`

In this case, the array is assumed to be one-dimensional, the keys are assumed to represent the names of various axes, and the values are assumed to represent the value of the coordinate on that axis. The values in the array have the same rules as $numerator and $denominator for the `Fraction` class (i.e. they can be of type int|float|numeric|DecimalInterface) and they will be coerced into the `ImmutableNumber` Value before being stored internally by the Tuple.

**This class implements the `CoordinateInterface`**