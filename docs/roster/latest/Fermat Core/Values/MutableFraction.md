# Samsara\Fermat\Values > MutableFraction

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Types\Fraction


### Implements

!!! signature interface "SimpleNumberInterface"
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers

    description
    :   *No description available*

!!! signature interface "FractionInterface"
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers

    description
    :   *No description available*

!!! signature interface "Stringable"
    namespace
    :   

    description
    :   *No description available*

!!! signature interface "NumberInterface"
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers

    description
    :   *No description available*

!!! signature interface "Hashable"
    namespace
    :   Ds

    description
    :   Hashable is an interface which allows objects to be used as keys.
    
     It’s an alternative to spl_object_hash(), which determines an object’s hash based on its handle: this means that two objects that are considered equal by an implicit definition would not treated as equal because they are not the same instance.



## Variables & Data


### Class Constants

!!! signature constant "MutableFraction::INFINITY"
    value
    :   'INF'

!!! signature constant "MutableFraction::NEG_INFINITY"
    value
    :   '-INF'



## Methods


### Constructor

!!! signature "public Fraction->__construct($numerator, $denominator, int $base)"
    **$numerator**

    description
    :   *No description available*

    **$denominator**

    description
    :   *No description available*

    **$base**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

    **Fraction->__construct Description**

    Fraction constructor.

---



### Inherited Methods

!!! signature "public Fraction->getValue()"
    **return**

    type
    :   string

    description
    :   *No description available*

---

!!! signature "public Fraction->getScale()"
    **return**

    type
    :   ?int

    description
    :   *No description available*

---

!!! signature "public Fraction->getBase()"
    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

---

!!! signature "public Fraction->getNumerator()"
    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

---

!!! signature "public Fraction->getDenominator()"
    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

---

!!! signature "public Fraction->isComplex()"
    **return**

    type
    :   bool

    description
    :   *No description available*

---

!!! signature "public Fraction->simplify()"
    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

---

!!! signature "public Fraction->abs()"
    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

---

!!! signature "public Fraction->absValue()"
    **return**

    type
    :   string

    description
    :   *No description available*

---

!!! signature "public Fraction->compare($number)"
    **$number**

    description
    :   *No description available*

    **return**

    type
    :   int

    description
    :   *No description available*

---

!!! signature "public Fraction->asDecimal($scale)"
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
    **return**

    type
    :   NumberInterface

    description
    :   *No description available*

---

!!! signature "public Fraction->getSmallestCommonDenominator(Samsara\Fermat\Types\Base\Interfaces\Numbers\FractionInterface $fraction)"
    **$fraction**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\FractionInterface

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

---

!!! signature "public Fraction->getAsBaseTenRealNumber()"
    **return**

    type
    :   string

    description
    :   *No description available*

---

!!! signature "public Number->setMode(int $mode)"
    **$mode**

    type
    :   int

    description
    :   *No description available*

    **return**

    type
    :   self

    description
    :   *No description available*

    **Number->setMode Description**

    Allows you to set a mode on a number to select the calculation methods.
    
     MODE_PRECISION: Use what is necessary to provide an answer that is accurate to the scale setting. MODE_NATIVE: Use built-in functions to perform the math, and accept whatever rounding or truncation this might cause.

---

!!! signature "public Number->setExtensions(bool $flag)"
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

    **Number->setExtensions Description**

    Allows the object to ignore PHP extensions (such a GMP) and use only the Fermat implementations. NOTE: This does not ignore ext-bcmath or ext-decimal, as those are necessary for the string math itself.

---

!!! signature "public Number->__toString()"
    **return**

    type
    :   string

    description
    :   *No description available*

---

!!! signature "public Number->hash()"
    **return**

    type
    :   string

    description
    :   *No description available*

    **Number->hash Description**

    Implemented to satisfy Hashable implementation

---

!!! signature "public Number->equals(mixed $object)"
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

    **Number->equals Description**

    Implemented to satisfy Hashable implementation

---

!!! signature "public Number->isImaginary()"
    **return**

    type
    :   bool

    description
    :   *No description available*

    **Number->isImaginary Description**

    This function returns true if the number is imaginary, and false in the number is real or complex

---

!!! signature "public Number->isReal()"
    **return**

    type
    :   bool

    description
    :   *No description available*

    **Number->isReal Description**

    This function returns true if the number is real, and false if the number is imaginary or complex

---

!!! signature "public Number->asReal()"
    **return**

    type
    :   string

    description
    :   *No description available*

---

!!! signature "public Number->asComplex()"
    **return**

    type
    :   Samsara\Fermat\Types\ComplexNumber

    description
    :   *No description available*

---

!!! signature "public Fraction->add($num)"
    **$num**

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

---

!!! signature "public Fraction->subtract($num)"
    **$num**

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

---

!!! signature "public Fraction->multiply($num)"
    **$num**

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

---

!!! signature "public Fraction->divide($num, ?int $scale)"
    **$num**

    description
    :   *No description available*

    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

---

!!! signature "public Fraction->pow($num)"
    **$num**

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

---

!!! signature "public Fraction->sqrt(?int $scale)"
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

---

!!! signature "public Fraction->isEqual($value)"
    **$value**

    description
    :   *No description available*

    **return**

    type
    :   bool

    description
    :   *No description available*

---

!!! signature "public Fraction->isGreaterThan($value)"
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
    **return**

    type
    :   bool

    description
    :   *No description available*

---

!!! signature "public Fraction->isPositive()"
    **return**

    type
    :   bool

    description
    :   *No description available*

---

!!! signature "public Fraction->isNatural()"
    **return**

    type
    :   bool

    description
    :   *No description available*

---

!!! signature "public Fraction->isWhole()"
    **return**

    type
    :   bool

    description
    :   *No description available*

---

!!! signature "public Fraction->isInt()"
    **return**

    type
    :   bool

    description
    :   *No description available*

---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."