# Base Conversion

Fermat can print your number in many different bases without needing to worry about how that might change the calculations. All calculations will continue to work correctly, no matter the base your number is in. It only effects the printing.

## Available Bases

The following bases are supported in Fermat:

- Base-2: `NumberBase::Two`
- Base-3: `NumberBase::Three`
- Base-4: `NumberBase::Four`
- Base-5: `NumberBase::Five`
- Base-6: `NumberBase::Six`
- Base-7: `NumberBase::Seven`
- Base-8: `NumberBase::Eight`
- Base-9: `NumberBase::Nine`
- Base-10: `NumberBase::Ten`
- Base-12: `NumberBase::Twelve`
- Base-16: `NumberBase::Sixteen`

## Changing The Base On A Number

Any number can have its base changed at any time. To do this, you simply use the `setBase(NumberBase $base)` method. Once this is set, all calls to `getValue()` without arguments will print in that base.

Alternatively, you can provide the desired base as part of the `getValue()` call to have it export in that base just once.

```php
<?php

use Samsara\Fermat\Core\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Core\Enums\NumberBase;

$five = new ImmutableDecimal(5);

// NOTE: setBase() is mutable for all values
$five->setBase(NumberBase::Four);

echo $five->getValue();                 // Prints: 11
echo $five->getValue(NumberBase::Five); // Prints: 10
echo $five->getValue(NumberBase::Two);  // Prints: 101
echo $five->getValue(NumberBase::Three);// Prints: 12
```

## Setting The Base During Instantiation

You can also set the base of an object while it is being instantiated.

```php
<?php

use Samsara\Fermat\Core\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Core\Enums\NumberBase;
use Samsara\Fermat\Core\Core\Numbers;

$five = new ImmutableDecimal(5, null, NumberBase::Five);

echo $five->getValue(); // Prints: 10

$six = Numbers::make(Numbers::IMMUTABLE, 6, null, NumberBase::Three);

echo $six->getValue();  // Prints: 20
```

## Instantiating With Non-Base-10 Input

If you want Fermat to assume the value you pass during instantiation is already *in* the target base, you can set the `$baseTenInput` argument to `false`.

```php
<?php

use Samsara\Fermat\Core\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Core\Enums\NumberBase;

$five = new ImmutableDecimal(10, null, NumberBase::Five, false);

echo $five->getValue();                // Prints: 10
echo $five->getValue(NumberBase::Ten); // Prints: 5
```

This is especially useful if you want to, for instance, convert a hex number into a Fermat value.

```php
<?php

use Samsara\Fermat\Core\Core\Values\ImmutableDecimal;
use Samsara\Fermat\Core\Core\Enums\NumberBase;

$color = "FF9933";
$moreGreen = "003300";

$baseColor = new ImmutableDecimal(
    value: $color, 
    base: NumberBase::Sixteen, 
    baseTenInput: false
);
$colorChange = new ImmutableDecimal(
    value: $moreGreen,
    base: NumberBase::Sixteen,
    baseTenInput: false
);

$newColor = $baseColor->add($colorChange);

echo $newColor->getValue(); // Prints: FFCC33
```