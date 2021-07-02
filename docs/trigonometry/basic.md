# Basic Trigonometry Methods

Trigonometry methods are only available on objects which implement `DecimalInterface`. If you want to do trigonometry functions on an object that implement `FractionInterface`, calling the `asDecimal()` method will return the numerator divided by the denominator as an instance of `ImmutableNumber` which implements the `DecimalInterface`.

**All trigonometry methods in this library assume that the Value is in radians.**

### sin(int $scale = null, bool $round = true): self

This method applies the sin function to the current Value. If $scale is null, the scale setting of the object is used. If a scale of greater than 99 is supplied as an argument, or if the object has a scale of greater than 99, the scale is silently reduced to 99.

If the $round argument is `true` the last digit will be rounded; if the $round argument is `false` the last digit will be truncated. It is important to note that the last digit (prior to rounding) is guaranteed to be accurate, so rounding will actually reduce the scale, in effect, by one. However, it will capture some of the behavior *after* the scale limit.

```php
<?php

use Samsara\Fermat\Values\ImmutableNumber;

$four = new ImmutableNumber(4);
$largeInt = new ImmutableNumber('1000000000000000000000000000');

echo $four->sin();       // Prints: "-0.7568024953"
echo $largeInt->sin(15); // Prints: "0.718063496139118"
```

### cos(int $scale = null, bool $round = true): self

This method applies the cos function to the current Value. If $scale is null, the scale setting of the object is used. If a scale of greater than 99 is supplied as an argument, or if the object has a scale of greater than 99, the scale is silently reduced to 99.

If the $round argument is `true` the last digit will be rounded; if the $round argument is `false` the last digit will be truncated. It is important to note that the last digit (prior to rounding) is guaranteed to be accurate, so rounding will actually reduce the scale, in effect, by one. However, it will capture some of the behavior *after* the scale limit.

```php
<?php

use Samsara\Fermat\Values\ImmutableNumber;

$four = new ImmutableNumber(4);
$largeInt = new ImmutableNumber('1000000000000000000000000000');

echo $four->cos();       // Prints: "-0.6536436209"
echo $largeInt->cos(15); // Prints: "-0.695977596990354"
```

### tan(int $scale = null, bool $round = true): self

This method applies the tan function to the current Value. If $scale is null, the scale setting of the object is used. If a scale of greater than 99 is supplied as an argument, or if the object has a scale of greater than 99, the scale is silently reduced to 99.

If the $round argument is `true` the last digit will be rounded; if the $round argument is `false` the last digit will be truncated. It is important to note that the last digit (prior to rounding) is guaranteed to be accurate, so rounding will actually reduce the scale, in effect, by one. However, it will capture some of the behavior *after* the scale limit.

```php
<?php

use Samsara\Fermat\Values\ImmutableNumber;

$twoPiDivThree = Numbers::make2Pi()->divide(3);
$piDivTwo = Numbers::makePi()->divide(2);

echo $twoPiDivThree->tan(14);        // Prints: "-1.73205080756888"
echo $twoPiDivThree->tan(14, false); // Prints: "-1.73205080756887"
echo $piDivTwo->tan();               // Prints: "INF"
```

### cot(int $scale = null, bool $round = true): self

This method applies the cot function to the current Value. If $scale is null, the scale setting of the object is used. If a scale of greater than 99 is supplied as an argument, or if the object has a scale of greater than 99, the scale is silently reduced to 99.

If the $round argument is `true` the last digit will be rounded; if the $round argument is `false` the last digit will be truncated. It is important to note that the last digit (prior to rounding) is guaranteed to be accurate, so rounding will actually reduce the scale, in effect, by one. However, it will capture some of the behavior *after* the scale limit.

```php
<?php

use Samsara\Fermat\Values\ImmutableNumber;

$five = new ImmutableNumber(5);

echo $five->cot(9);        // Prints: "-0.295812916"
echo $five->cot(9, false); // Prints: "-0.295812915"
```

### sec(int $scale = null, bool $round = true): self

This method applies the sec function to the current Value. If $scale is null, the scale setting of the object is used. If a scale of greater than 99 is supplied as an argument, or if the object has a scale of greater than 99, the scale is silently reduced to 99.

If the $round argument is `true` the last digit will be rounded; if the $round argument is `false` the last digit will be truncated. It is important to note that the last digit (prior to rounding) is guaranteed to be accurate, so rounding will actually reduce the scale, in effect, by one. However, it will capture some of the behavior *after* the scale limit.

```php
<?php

use Samsara\Fermat\Values\ImmutableNumber;

$five = new ImmutableNumber(5);

echo $five->sec(9);        // Prints: "3.525320086"
echo $five->sec(9, false); // Prints: "3.525320085"
```

### csc(int $scale = null, bool $round = true): self

This method applies the csc function to the current Value. If $scale is null, the scale setting of the object is used. If a scale of greater than 99 is supplied as an argument, or if the object has a scale of greater than 99, the scale is silently reduced to 99.

If the $round argument is `true` the last digit will be rounded; if the $round argument is `false` the last digit will be truncated. It is important to note that the last digit (prior to rounding) is guaranteed to be accurate, so rounding will actually reduce the scale, in effect, by one. However, it will capture some of the behavior *after* the scale limit.

```php
<?php

use Samsara\Fermat\Values\ImmutableNumber;

$five = new ImmutableNumber(5);

echo $five->csc(9);        // Prints: "-1.042835213"
echo $five->csc(9, false); // Prints: "-1.042835212"
```