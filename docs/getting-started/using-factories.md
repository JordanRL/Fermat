Fermat provides factory classes to make it easier to get instances of the various Value classes. The available factories are:

- `Samsara\Fermat\Collections`
- `Samsara\Fermat\ComplexNumbers`
- `Samsara\Fermat\Matrices`
- `Samsara\Fermat\Numbers`

All factories are classes that have only static methods and constants. 

# The Collections Factory Class

The `Samsara\Fermat\Collections` factory class currently has no methods or constants, and exists as a placeholder.

# The ComplexNumbers Factory Class

The `Samsara\Fermat\ComplexNumbers` factory class allows you to create instances of the Value classes which implement the `ComplexNumberInterface`.

# The Matrices Factory Class

The `Samsara\Fermat\Matrices` factory class provides access to several pre-built matrices that may be useful in common situations.

###### Matrices::zeroMatrix(string $type, int $rows, int $columns)

This factory method returns an instance of the specified matrix type with the given dimensions where all values in the matrix are the number zero.

###### Matrices::onesMatrix(string $type, int $rows, int $columns)

This factory method returns an instance of the specified matrix type with the given dimensions where all values in the matrix are the number one.

###### Matrices::identityMatrix(string $type, int $size)

This factory method returns a square matrix where the dimensions match the integer given in `$size`. This matrix is an identity matrix, which is often used in matrix math, where the diagonal consists of ones, and all other values are zero.

!!! tip "For Example"
    An identity matrix of size three would look like:
    
    [1 0 0]  
    [0 1 0]  
    [0 0 1]

###### Matrices::cofactorMatrix(string $type, int $size)

This factory method returns a square matrix where the dimensions match the integer given in `$size`. The matrix is filled with alternating values of 1 and -1 in a checkerboard pattern, starting with positive 1 in position [0, 0].

When multiplied by another matrix, this will swap the sign of every other value in the matrix.

!!! tip "For Example"
    A cofactor matrix of size three would look like:
    
    [+ - +]  
    [- + -]  
    [+ - +]

# The Numbers Factory Class

The `Samsara\Fermat\Numbers` factory class provides a way to use the Value classes which implement the `SimpleNumberInterface` in Fermat without being as specific as those classes may require. Consider the following code:

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

// Value in base5
$five = Numbers::make(Numbers::IMMUTABLE, '10', null, 5); 
// Value in base10
$ten = Numbers::make(Numbers::IMMUTABLE, '10');

echo $ten->add($five); // Prints: "15" (The sum in base10)
echo $five->add($ten); // Prints: "30" (The sum in base5)
```

You can convert the base of a number freely:

```php
<?php

use Samsara\Fermat\Numbers;

// Value in base5
$five = Numbers::make(Numbers::IMMUTABLE, '10', null, 5); 
// Value in base10
$ten = Numbers::make(Numbers::IMMUTABLE, '10');

$fifteen = $five->add($ten);

echo $fifteen; // Prints: "30" (The sum in base5)
echo $fifteen->convertToBase(10); // Prints: "15" (The sum in base10)
echo $fifteen->convertToBase(16); // Prints: "F" (The sum in base16)
```

You can also pass strings, integers, and floats directly to the arithmetic methods instead of instances of Values, but whenever you do this it will always be assumed that the number being passed as an argument is in base10:

```php
<?php

use Samsara\Fermat\Numbers;

// Value in base5
$five = Numbers::make(Numbers::IMMUTABLE, '10', null, 5); 

echo $five->add(10)->convertToBase(10); 
// Prints: "15" (The sum in base10)
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

echo get_class($first);  // "Samsara\Fermat\Values\ImmutableNumber"
echo get_class($second); // "Samsara\Fermat\Values\ImmutableNumber"
echo get_class($third);  // "Samsara\Fermat\Values\ImmutableNumber"
echo get_class($fourth); // "Samsara\Fermat\Values\ImmutableNumber"
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