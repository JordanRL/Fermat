# How To Use Fermat In Place Of Scalars

This outlines the way you should configure and use Fermat if you want to use it as a replacement for the **integer** and **float** scalar types in your application.

You might want to do this if:

- You want a fluent, object-oriented interface for your numbers.
- You want to be able to use non-integer and non-float numbers, such as **fractions** or **imaginary numbers**.
- You want the option at specific points in your application to increase the scale higher than the scale offered by native PHP types.
- You want to be able to provide number types for parameters and properties that are more specific than integer and float.

## Set The Default Calculation Mode To Native

In the bootstrap phase of your application, you should set `CalcMode::Native` as your global [Calculation Mode](../configuration/calculation-modes.md). This can be accomplished with:

```php
<?php

use Samsara\Fermat\Provider\CalculationModeProvider;
use Samsara\Fermat\Enums\CalcMode;

CalculationModeProvider::setCurrentMode(CalcMode::Native);
```

If this is set in your program before you begin using any of the Fermat values, they will all automatically use the same mode when performing calculations.

!!! caution "Scale Is Ignored For Native Mode"
    If you use Fermat with the Native mode setting, the scale on values are ignored for all calculations, and your object will be set to whatever value the PHP engine returns for the equivalent operation.

    In general this results in somewhere between 10 and 12 digits of accuracy for numbers near 0, with fewer digits of accuracy as you move further away from 0 and the density of possible values a float can represent is reduced.

### Precision Mode At Specific Points

If you mostly want to use Fermat in Native mode, but have a few places in your application where you want to take advantage of increased scale, you can do this on each individual object instance.

```php
<?php

use Samsara\Fermat\Values\ImmutableDecimal;
use Samsara\Fermat\Enums\CalcMode;
use Samsara\Fermat\Numbers;

$num = Numbers::make(Numbers::IMMUTABLE, '25', $myDesiredScale);

// The setMode() method is mutable for all objects
$num->setMode(CalcMode::Precision);

// $answer is now an instance of ImmutableDecimal with mode set
// to CalcMode::Precision as well
$answer = $num->tan();
```

## Use Immutables

The `ImmutableDecimal` and `ImmutableFraction` value classes should be used when your aim is to replace scalar types. Scalars (and PHP variables in general) behave immutably.

### Incrementing and Decrementing

The `$foo++` and `$foo--` operations modify the variable in place. To duplicate this behavior with immutable classes, try to following:

```php
<?php

use Samsara\Fermat\Values\ImmutableDecimal;

$num = new ImmutableDecimal(5);

// Analogous to the $num++ case
$num = $num->add(1);

// Analogous to the $num-- case
$num = $num->subtract(1);
```

## When You Need An Actual Scalar

If you are using Fermat in place of scalar types, you might encounter a point when you need an actual scalar value for your program. A good example might be for an argument to a function.

In these cases, you can use the `asInt()` and `asFloat()` methods to get the current value in the corresponding type.

## Helper Methods

There are some useful helper methods in situations such as this. The `isInt()`, `isNatural()` and `isWhole()` methods will all return a boolean telling you whether the current value is a whole number, with no decimal component. This can let you know if using the `asInt()` method will lose any precision.

The opposite side can be checked with the `isFloat()` method, which will return true if the decimal component of the number has anything other that zeros in it.

The `isPositive()` and `isNegative()` methods will give a boolean answering their respective questions.

The `numberOfLeadingZeros()` method will tell you how many zeros there are between the decimal point and the first non-zero digit in the decimal portion.

So you could write some code that casts the value object to an integer only if the decimal portion is less than 1/1000 with the following code:

```php
<?php

use Samsara\Fermat\Values\ImmutableDecimal;

$num = new ImmutableDecimal('5.0009');

if ($num->numberOfLeadingZeros() >= 3) {
    $scalarNum = $num->asInt();
} else {
    $scalarNum = $num->asFloat();
}
```