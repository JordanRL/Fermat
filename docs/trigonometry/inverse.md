# Inverse Trigonometry Methods

Trigonometry methods are only available on objects which implement `DecimalInterface`. If you want to do trigonometry functions on an object that implement `FractionInterface`, calling the `asDecimal()` method will return the numerator divided by the denominator as an instance of `ImmutableNumber` which implements the `DecimalInterface`.

**All trigonometry methods in this library assume that the Value is in radians.**

### arcsin(int $scale = null, bool $round = true): self

This method applies the arcsin function to the current Value. If $scale is null, the scale setting of the object is used. If a scale of greater than 99 is supplied as an argument, or if the object has a scale of greater than 99, the scale is silently reduced to 99.

If the $round argument is `true` the last digit will be rounded; if the $round argument is `false` the last digit will be truncated. It is important to note that the last digit (prior to rounding) is guaranteed to be accurate, so rounding will actually reduce the scale, in effect, by one. However, it will capture some of the behavior *after* the scale limit.

**Note:** The `arcsin()` function has a domain of −1 ≤ x ≤ 1 and a range of −π/2 ≤ y ≤ π/2.

### arccos(int $scale = null, bool $round = true): self

This method applies the arccos function to the current Value. If $scale is null, the scale setting of the object is used. If a scale of greater than 99 is supplied as an argument, or if the object has a scale of greater than 99, the scale is silently reduced to 99.

If the $round argument is `true` the last digit will be rounded; if the $round argument is `false` the last digit will be truncated. It is important to note that the last digit (prior to rounding) is guaranteed to be accurate, so rounding will actually reduce the scale, in effect, by one. However, it will capture some of the behavior *after* the scale limit.

**Note:** The `arccos()` function has a domain of −1 ≤ x ≤ 1 and a range of 0 ≤ y ≤ π.

### arctan(int $scale = null, bool $round = true): self

This method applies the arctan function to the current Value. If $scale is null, the scale setting of the object is used. If a scale of greater than 99 is supplied as an argument, or if the object has a scale of greater than 99, the scale is silently reduced to 99.

If the $round argument is `true` the last digit will be rounded; if the $round argument is `false` the last digit will be truncated. It is important to note that the last digit (prior to rounding) is guaranteed to be accurate, so rounding will actually reduce the scale, in effect, by one. However, it will capture some of the behavior *after* the scale limit.

**Note:** The `arctan()` function has a domain of all real numbers and a range of -π/2 < y < π/2.

```php
<?php

use Samsara\Fermat\Values\ImmutableNumber;

$five = new ImmutableNumber(5);

echo $five->arctan(9);        // Prints: "1.373400767"
echo $five->arctan(9, false); // Prints: "1.373400767"
```

### arccot(int $scale = null, bool $round = true): self

This method applies the arccot function to the current Value. If $scale is null, the scale setting of the object is used. If a scale of greater than 99 is supplied as an argument, or if the object has a scale of greater than 99, the scale is silently reduced to 99.

If the $round argument is `true` the last digit will be rounded; if the $round argument is `false` the last digit will be truncated. It is important to note that the last digit (prior to rounding) is guaranteed to be accurate, so rounding will actually reduce the scale, in effect, by one. However, it will capture some of the behavior *after* the scale limit.

**Note:** The `arccot()` function has a domain of all real numbers and a range of 0 < y < π.

### arcsec(int $scale = null, bool $round = true): self

This method applies the arcsec function to the current Value. If $scale is null, the scale setting of the object is used. If a scale of greater than 99 is supplied as an argument, or if the object has a scale of greater than 99, the scale is silently reduced to 99.

If the $round argument is `true` the last digit will be rounded; if the $round argument is `false` the last digit will be truncated. It is important to note that the last digit (prior to rounding) is guaranteed to be accurate, so rounding will actually reduce the scale, in effect, by one. However, it will capture some of the behavior *after* the scale limit.

**Note:** The `arcsec()` function has a domain of x ≤ −1 or 1 ≤ x and a range of 0 ≤ y < π/2 or π/2 < y ≤ π.

### arccsc(int $scale = null, bool $round = true): self

This method applies the arccsc function to the current Value. If $scale is null, the scale setting of the object is used. If a scale of greater than 99 is supplied as an argument, or if the object has a scale of greater than 99, the scale is silently reduced to 99.

If the $round argument is `true` the last digit will be rounded; if the $round argument is `false` the last digit will be truncated. It is important to note that the last digit (prior to rounding) is guaranteed to be accurate, so rounding will actually reduce the scale, in effect, by one. However, it will capture some of the behavior *after* the scale limit.

**Note:** The `arccsc()` function has a domain of x ≤ −1 or 1 ≤ x and a range of −π/2 ≤ y < 0 or 0 < y ≤ π/2.