You can also directly instantiate the Value classes if you wish, and sometimes it is desirable to do so.

## ImmutableNumber and MutableNumber

These classes extend the `Samsara\Fermat\Types\Decimal` abstract class, and their constructors have the following signature:

###### __construct(int|float|numeric $value, $precision = 10, $base = 10)

**These classes implement the `SimpleNumberInterface` and `DecimalInterface`**

## ImmutableFraction and MutableFraction

These classes extend the `Samsara\Fermat\Types\Fraction` abstract class, and their constructors have the following signature:

###### __construct(int|float|numeric|DecimalInterface $numerator, int|float|numeric|DecimalInterface $denominator, $base = 10)

Note that if either $numerator or $denominator are not whole numbers, they will be rounded. Both the numerator and denominator are stored internally by `Fraction` as instances of `ImmutableNumber`, and they will be coerced into this Value.

**These classes implement the `SimpleNumberInterface` and `FractionInterface`**

## CartesianCoordinate

This class extends the `Samsara\Fermat\Types\Coordinate` class, and its constructor has the following signature:

###### __construct(array $data)

In this case, the array is assumed to be one-dimensional, the keys are assumed to represent the names of various axes, and the values are assumed to represent the value of the coordinate on that axis. The values in the array have the same rules as $numerator and $denominator for the `Fraction` class (i.e. they can be of type int|float|numeric|DecimalInterface) and they will be coerced into the `ImmutableNumber` Value before being stored internally by the Tuple.

**This class implements the `CoordinateInterface`**