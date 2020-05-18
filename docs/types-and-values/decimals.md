Decimals represent numbers which have a decimal representation. They can be equivalent to either a `float` or an `integer`, and their `$scale` setting determines how many places after the decimal are calculated for operations which would affect them.

# Abstract Class: Decimal

The following interfaces and traits are available on classes which extend `Decimal`

## Interfaces

--8<-- "has-interface/hashable.md"

--8<-- "has-interface/base-conversion.md"

--8<-- "has-interface/number.md"

--8<-- "has-interface/simple-number.md"

--8<-- "has-interface/decimal.md"

## Traits

--8<-- "uses-trait/arithmeticsimple.md"

--8<-- "uses-trait/comparison.md"

--8<-- "uses-trait/integer-math.md"

--8<-- "uses-trait/trigonometry.md"

--8<-- "uses-trait/inverse-trigonometry.md"

--8<-- "uses-trait/log.md"

--8<-- "uses-trait/scale.md"

## Abstract Methods

The following abstract methods must be implemented on classes which extend `Decimal`

!!! signature "abstract protected function setValue(string $value, int $scale = null, int $base = 10)"
    $value
    :   The new value that will be set in the same format as the output of **getValue(10)**
    
    $scale
    :   The maximum number of digits after the decimal that the value can contain
    
    $base
    :   The base of the value in the final instance
    
    return
    :   An instance of the current class with the given arguments set as properties

This method controls the behavior of setting the `$value` property, and its different implementations represent the main difference between mutable and immutable versions.

!!! signature "abstract public function continuousModulo($mod): DecimalInterface"
    $mod
    :   The modulus that will be taken; the base which will be used to calculate the remainder; can be a decimal value
    
    return
    :   The remainder of dividing the current value by the **$mod**

This method comes from `DecimalInterface` and must be implemented by the extending class. This is because it might be undesirable for this method to be mutable, even for a mutable class. It takes a decimal value as its input, and returns the remainder of a division operation, even if the number provided is not an integer.

# Available Value Objects

The following implementations of `Decimal` are included with Fermat. These classes may be provided for any argument which accepts a `DecimalInterface` as a value.

## ImmutableDecimal

A number which can be represented as a decimal and has a maximum scale of $`2^{63}`$ digits. This value is immutable, and all methods called on instances of the class will return a new instance of the same class while leaving the existing instance at its original value.

## MutableDecimal

A number which can be represented as a decimal and has a maximum scale of $`2^{63}`$ digits. This value is mutable, and all methods called on instances of the class will return the same instance after modification, while the previous value will be lost.