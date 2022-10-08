# Samsara\Fermat\Values > ImmutableFraction

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Types\Fraction


### Implements

!!! signature interface "SimpleNumberInterface"
    ##### SimpleNumberInterface
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers

    description
    :   

    *No description available*

!!! signature interface "FractionInterface"
    ##### FractionInterface
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers

    description
    :   

    *No description available*

!!! signature interface "Stringable"
    ##### Stringable
    namespace
    :   

    description
    :   

    *No description available*

!!! signature interface "NumberInterface"
    ##### NumberInterface
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers

    description
    :   

    *No description available*

!!! signature interface "Hashable"
    ##### Hashable
    namespace
    :   Ds

    description
    :   

    Hashable is an interface which allows objects to be used as keys.
    
     It’s an alternative to spl_object_hash(), which determines an object’s hash based on its handle: this means that two objects that are considered equal by an implicit definition would not treated as equal because they are not the same instance.



## Variables & Data


### Class Constants

!!! signature constant "ImmutableFraction::INFINITY"
    ##### INFINITY
    value
    :   'INF'

!!! signature constant "ImmutableFraction::NEG_INFINITY"
    ##### NEG_INFINITY
    value
    :   '-INF'



## Methods


### Constructor

!!! signature "public Fraction->__construct($numerator, $denominator, NumberBase $base)"
    ##### __construct
    **$numerator**

    description
    :   *No description available*

    **$denominator**

    description
    :   *No description available*

    **$base**

    type
    :   NumberBase

    description
    :   
    
    

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

    ###### __construct() Description:

    Fraction constructor.
    
---



### Inherited Methods

!!! signature "public Fraction->getValue()"
    ##### getValue
    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public Fraction->getScale()"
    ##### getScale
    **return**

    type
    :   ?int

    description
    :   *No description available*
    
---

!!! signature "public Fraction->getNumerator()"
    ##### getNumerator
    **return**

    type
    :   mixed|DecimalInterface|ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Fraction->getDenominator()"
    ##### getDenominator
    **return**

    type
    :   mixed|DecimalInterface|ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Fraction->isComplex()"
    ##### isComplex
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public Fraction->simplify()"
    ##### simplify
    **return**

    type
    :   FractionInterface|Fraction

    description
    :   *No description available*
    
---

!!! signature "public Fraction->abs()"
    ##### abs
    **return**

    type
    :   $this|Base\Interfaces\Numbers\DecimalInterface|FractionInterface|NumberInterface|Fraction

    description
    :   *No description available*
    
---

!!! signature "public Fraction->absValue()"
    ##### absValue
    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public Fraction->compare($value)"
    ##### compare
    **$value**

    description
    :   *No description available*

    **return**

    type
    :   int

    description
    :   *No description available*
    
---

!!! signature "public Fraction->asDecimal($scale)"
    ##### asDecimal
    **$scale**

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Fraction->getGreatestCommonDivisor()"
    ##### getGreatestCommonDivisor
    **return**

    type
    :   NumberInterface

    description
    :   *No description available*
    
---

!!! signature "public Fraction->getSmallestCommonDenominator(FractionInterface $fraction)"
    ##### getSmallestCommonDenominator
    **$fraction**

    type
    :   FractionInterface

    description
    :   *No description available*

    **return**

    type
    :   NumberInterface

    description
    :   *No description available*
    
---

!!! signature "public Fraction->getAsBaseTenRealNumber()"
    ##### getAsBaseTenRealNumber
    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public Number->setMode(CalcMode $mode)"
    ##### setMode
    **$mode**

    type
    :   CalcMode

    description
    :   *No description available*

    **return**

    type
    :   self

    description
    :   *No description available*

    ###### setMode() Description:

    Allows you to set a mode on a number to select the calculation methods.
    
     MODE_PRECISION: Use what is necessary to provide an answer that is accurate to the scale setting. MODE_NATIVE: Use built-in functions to perform the math, and accept whatever rounding or truncation this might cause.
    
---

!!! signature "public Number->getMode()"
    ##### getMode
    **return**

    type
    :   Samsara\Fermat\Enums\CalcMode

    description
    :   *No description available*
    
---

!!! signature "public Number->setExtensions(bool $flag)"
    ##### setExtensions
    **$flag**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   self

    description
    :   *No description available*

    ###### setExtensions() Description:

    Allows the object to ignore PHP extensions (such a GMP) and use only the Fermat implementations. NOTE: This does not ignore ext-bcmath or ext-decimal, as those are necessary for the string math itself.
    
---

!!! signature "public Number->__toString()"
    ##### __toString
    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public Number->hash()"
    ##### hash
    **return**

    type
    :   string

    description
    :   *No description available*

    ###### hash() Description:

    Implemented to satisfy Hashable implementation
    
---

!!! signature "public Number->equals(mixed $object)"
    ##### equals
    **$object**

    type
    :   mixed

    description
    :   *No description available*

    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### equals() Description:

    Implemented to satisfy Hashable implementation
    
---

!!! signature "public Number->isImaginary()"
    ##### isImaginary
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isImaginary() Description:

    This function returns true if the number is imaginary, and false in the number is real or complex
    
---

!!! signature "public Number->isReal()"
    ##### isReal
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isReal() Description:

    This function returns true if the number is real, and false if the number is imaginary or complex
    
---

!!! signature "public Number->asReal()"
    ##### asReal
    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public Number->asComplex()"
    ##### asComplex
    **return**

    type
    :   Samsara\Fermat\Values\ImmutableComplexNumber

    description
    :   *No description available*
    
---

!!! signature "public Number->getBase()"
    ##### getBase
    **return**

    type
    :   Samsara\Fermat\Enums\NumberBase

    description
    :   *No description available*
    
---

!!! signature "public Fraction->add($num)"
    ##### add
    **$num**

    description
    :   *No description available*

    **return**

    type
    :   $this|DecimalInterface|Fraction|ImmutableComplexNumber|ImmutableDecimal|MutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Fraction->subtract($num)"
    ##### subtract
    **$num**

    description
    :   *No description available*

    **return**

    type
    :   $this|DecimalInterface|Fraction|ImmutableComplexNumber|ImmutableDecimal|MutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Fraction->multiply($num)"
    ##### multiply
    **$num**

    description
    :   *No description available*

    **return**

    type
    :   $this|DecimalInterface|Fraction|ImmutableDecimal|MutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Fraction->divide($num, int|null $scale)"
    ##### divide
    **$num**

    description
    :   *No description available*

    **$scale**

    type
    :   int|null

    description
    :   *No description available*

    **return**

    type
    :   $this|DecimalInterface|Fraction|ImmutableDecimal|MutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Fraction->pow($num)"
    ##### pow
    **$num**

    description
    :   *No description available*

    **return**

    type
    :   DecimalInterface|Fraction|ImmutableComplexNumber

    description
    :   *No description available*
    
---

!!! signature "public Fraction->sqrt(int|null $scale)"
    ##### sqrt
    **$scale**

    type
    :   int|null

    description
    :   *No description available*

    **return**

    type
    :   DecimalInterface|Fraction

    description
    :   *No description available*
    
---

!!! signature "public Fraction->isEqual(NumberInterface|int|string|float $value)"
    ##### isEqual
    **$value**

    type
    :   NumberInterface|int|string|float

    description
    :   *No description available*

    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public Fraction->isGreaterThan($value)"
    ##### isGreaterThan
    **$value**

    description
    :   *No description available*

    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public Fraction->isLessThan($value)"
    ##### isLessThan
    **$value**

    description
    :   *No description available*

    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public Fraction->isGreaterThanOrEqualTo($value)"
    ##### isGreaterThanOrEqualTo
    **$value**

    description
    :   *No description available*

    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public Fraction->isLessThanOrEqualTo($value)"
    ##### isLessThanOrEqualTo
    **$value**

    description
    :   *No description available*

    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public Fraction->isNegative()"
    ##### isNegative
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public Fraction->isPositive()"
    ##### isPositive
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public Fraction->isNatural()"
    ##### isNatural
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public Fraction->isWhole()"
    ##### isWhole
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public Fraction->isInt()"
    ##### isInt
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."