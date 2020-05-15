# Working With `NumberInterface`

Arithmetic can be performed on any class that implements the `NumberInterface`, and the rules for using arithmetic methods are consistent and straight-forward: you can put any value that is valid for an `ImmutableNumber` constructor in, or you can put in any instance of an object that implements `NumberInterface` itself.

!!! potential-bugs "You Might Not Expect"
    If two objects which have different calculation modes are used in an arithmetic operation, the calculation mode of the object which makes the function call is used, and the calculation mode of the object supplied as an argument is ignored.
    
    Additionally, the object returned will be of the same class as the object making the function call if that is possible. This means that fractions will be converted to decimals when they are the argument for a decimal function call, or visa versa.
    
    If the result **must** be represented in a particular way, such as with complex numbers, the returned value will be the immutable version of the class that can respresent the result. This means adding two classes that implement `DecimalInterface` can return a class that implements `ComplexNumberInterface` if one is an imaginary number, and one is a real number.
    
!!! see-also "See Also"
    This is a test box. It will have text that explains things or points to a particular location.
    
    It may also contain several lines.
    
!!! example "For Example"
    This is a test box. It will have text that explains things or points to a particular location.
    
    It may also contain several lines.
    
!!! warning "Warning"
    This is a test box. It will have text that explains things or points to a particular location.
    
    It may also contain several lines.
    
!!! danger "Danger"
    This is a test box. It will have text that explains things or points to a particular location.
    
    It may also contain several lines.
    
!!! caution "Caution"
    This is a test box. It will have text that explains things or points to a particular location.
    
    It may also contain several lines.
    
!!! tip "Tip"
    This is a test box. It will have text that explains things or points to a particular location.
    
    It may also contain several lines.
    
!!! note "Note"
    This is a test box. It will have text that explains things or points to a particular location.
    
    It may also contain several lines.

The following arithmetic methods are available.

### add(int|float|numeric|NumberInterface $num): self

This adds the argument to the Value using the `ArithmeticProvider` or the native `+` operator depending on the calculation mode of the original object. 

When an object that implements `DecimalInterface` and another that implements `FractionInterface` are added together, the one that is provided as an argument is coerced into the type of original object. The result is returned as an instance of a value object, depending on the result of the calculation. 

### subtract(int|float|numeric|NumberInterface $num): self

This subtracts the argument from the Value using the `ArithmeticProvider` (which uses the BCMath library internally) and returns the newly calculated Value.

When an object that implements `DecimalInterface` and another that implements `FractionInterface` are subtracted, the one that is provided as an argument is coerced into the type of original object. For example:

```php
<?php

use Samsara\Fermat\Values\ImmutableNumber;
use Samsara\Fermat\Values\ImmutableFraction;

$five = new ImmutableNumber(5);
$oneQuarter = new ImmutableFraction(1, 4);

echo $five->subtract($oneQuarter); // Prints: "4.75" 
// The asDecimal() method is called on $oneQuarter

echo $oneQuarter->subtract($five); // Prints: "-19/4" 
// Calls getValue() on $five and instantiates a new ImmutableFraction
```

### multiply(int|float|numeric|NumberInterface $num): self

This multiplies the argument from the Value using the `ArithmeticProvider` (which uses the BCMath library internally) and returns the newly calculated Value.

When an object that implements `DecimalInterface` and another that implements `FractionInterface` are multiplied, the one that is provided as an argument is coerced into the type of original object. For example:

```php
<?php

use Samsara\Fermat\Values\ImmutableNumber;
use Samsara\Fermat\Values\ImmutableFraction;

$five = new ImmutableNumber(5);
$oneQuarter = new ImmutableFraction(1, 4);

echo $five->multiply($oneQuarter); // Prints: "1.25" 
// The asDecimal() method is called on $oneQuarter

echo $oneQuarter->multiply($five); // Prints: "5/4" 
// Calls getValue() on $five and instantiates a new ImmutableFraction
```

### divide(int|float|numeric|NumberInterface $num, int $precision = null): self

This divides the argument from the Value using the `ArithmeticProvider` (which uses the BCMath library internally) and returns the newly calculated Value.

The $precision argument tells the Value how many decimals of accuracy you want in your division (if that is relevant to the division), and defaults to the precision of the *calling* object if null. The default precision of a Value, if you do not set it during instantiation, is 10.

When an object that implements `DecimalInterface` and another that implements `FractionInterface` are divided, the one that is provided as an argument is coerced into the type of original object. For example:

```php
<?php

use Samsara\Fermat\Values\ImmutableNumber;
use Samsara\Fermat\Values\ImmutableFraction;

$five = new ImmutableNumber(5);
$oneQuarter = new ImmutableFraction(1, 4);

echo $five->divide($oneQuarter); // Prints: "20" 
// The asDecimal() method is called on $oneQuarter

echo $oneQuarter->divide($five); // Prints: "1/20" 
// Calls getValue() on $five and instantiates a new ImmutableFraction
```

### pow(int|float|numeric|NumberInterface $num): self

This raises the Value to the power of $num, and will work even if $num has a decimal component. **NOTE:** This method will only return Real numbers as Values, as Complex numbers are not currently supported.

When an object that implements `DecimalInterface` and another that implements `FractionInterface` are raised to a power, the one that is provided as an argument is coerced into the type of original object. For example:

```php
<?php

use Samsara\Fermat\Values\ImmutableNumber;
use Samsara\Fermat\Values\ImmutableFraction;

$five = new ImmutableNumber(5);
$oneQuarter = new ImmutableFraction(1, 4);

echo $five->pow($oneQuarter); // Prints: "1.4953487812" 
// The asDecimal() method is called on $oneQuarter
// Because $precision was not supplied to the constructor, $precision is 10

echo $oneQuarter->pow($five); // Prints: "1/1024" 
// Calls getValue() on $five and instantiates a new ImmutableFraction
```

### sqrt(): self

This takes the square root of the current object.

```php
<?php

use Samsara\Fermat\Values\ImmutableNumber;
use Samsara\Fermat\Values\ImmutableFraction;

$five = new ImmutableNumber(5);
$oneQuarter = new ImmutableFraction(1, 4);

echo $five->sqrt(); // Prints: "2.2360679775" 
// Because $precision was not supplied to the constructor, $precision is 10

echo $oneQuarter->sqrt(); // Prints: "1/2" 
```

# Working with `DecimalInterface`

Additional arithmetic can be performed on objects that implement the `DecimalInterface`, and the rules for using arithmetic methods are the same as with `NumberInterface` methods: you can put any value that is valid for an `ImmutableNumber` constructor in, or you can put in any instance of an object that implements `DecimalInterface` itself.

### factorial(): self

This takes the factorial of the current Value, however the Value must be a whole number.

```php
<?php

use Samsara\Fermat\Values\ImmutableNumber;

$five = new ImmutableNumber(5);

echo $five->factorial(); // Prints: "120"
```

### doubleFactorial(): self, semiFactorial(): self

This takes the double factorial (x!!) of the current Value. **Note:** If you are not familiar with this operation, it is **NOT** the same as taking the factorial twice: (x!)!. Instead it is like taking a factorial where you decrease the number by two instead of one:

10! = 10 \* 9 \* 8 \* 7 \* 6 \* 5 \* 4 \* 3 \* 2 \* 1  
10!! = 10 \* 8 \* 6 \* 4 \* 2

```php
<?php

use Samsara\Fermat\Values\ImmutableNumber;

$five = new ImmutableNumber(5);

echo $five->doubleFactorial(); // Prints: "15"
```

### ln(int $precision = 10, $round = true): self

This takes the natural log of the current Value, accurate to $precision decimal places.

If the $round argument is `true` the last digit will be rounded; if the $round argument is `false` the last digit will be truncated. It is important to note that the last digit (prior to rounding) is guaranteed to be accurate, so rounding will actually reduce the precision, in effect, by one. However, it will capture some of the behavior *after* the precision limit.

```php
<?php

use Samsara\Fermat\Values\ImmutableNumber;

$five = new ImmutableNumber(5);

echo $five->ln(11); // Prints: "1.60943791243"
```

### log10(int $precision = 10, $round = true): self

This takes the log base10 of the current Value, accurate to $precision decimal places.

If the $round argument is `true` the last digit will be rounded; if the $round argument is `false` the last digit will be truncated. It is important to note that the last digit (prior to rounding) is guaranteed to be accurate, so rounding will actually reduce the precision, in effect, by one. However, it will capture some of the behavior *after* the precision limit.

```php
<?php

use Samsara\Fermat\Values\ImmutableNumber;

$five = new ImmutableNumber(5);

echo $five->log10(11); // Prints: "0.69897000434"
```

# Working With `ArithmeticProvider` Directly

The `ArithmeticProvider` is a wrapper for the BCMath library, and it is ultimately what performs most operations inside the objects that implement `NumberInterface`, `DecimalInterface`, `FractionInterface`, and `CoordinateInterface`.

All of its methods are static, and can be accessed without instantiating the class.

All arguments to this provider must be strings which only contain numeric values.

### add(string $number1, string $number2): string

Calls `bcadd($number1, $number2)` with a scale setting of 100.

### subtract(string $left, string $right): string

Calls `bcsub($left, $right)` with a scale setting of 100.

### multiply(string $number1, string $number2): string

Calls `bcmul($number1, $number2)` with a scale setting of 100.

### divide(string $numerator, string $denominator, int $precision = 100): string

Calls `bcdiv($numerator, $denominator, $precision)`.

### pow(string $base, string $exponent): string

Calls `bcpow($base, $exponent)` with a scale of 100. **Note:** Unlike the `pow()` method on `NumberInterface` objects, the exponent **must** be a whole number.

### squareRoot(string $number, int $precision = 100): string

Calls `bcsqrt($number, $precision)`.

### modulo(string $number, string $modulo): string

Calls `bcmod($number, $modulo)`. **Note:** Unlike the `continuousModulo()` method on `DecimalInterface` objects, the modulus **must** be a whole number.

### compare(string $left, string $right, int $scale = 100): int

Calls `bccomp($left, $right, $scale)`. Its output format is identical to the `compare()` helper method on `NumberInterface` objects.

### powmod(string left, string $right, string $modulus, int $scale = 100): string

Calls `bcpowmod($left, $right, $modulus, $scale)`.

### factorial(string $number): string

Calls `bcmul()` repeatedly to return the factorial. **Note:** The `factorial()` method on `DecimalInterface` objects does not use this method, and instead uses `gmp_fact()` if the method is available, and makes repeated calls to `multiply()` if it is not.
