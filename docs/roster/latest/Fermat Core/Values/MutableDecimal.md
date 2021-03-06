# Samsara\Fermat\Values > MutableDecimal

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Types\Decimal


### Implements

!!! signature interface "SimpleNumberInterface"
    ##### SimpleNumberInterface
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers

    description
    :   

    *No description available*

!!! signature interface "BaseConversionInterface"
    ##### BaseConversionInterface
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Characteristics

    description
    :   

    *No description available*

!!! signature interface "DecimalInterface"
    ##### DecimalInterface
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

!!! signature constant "MutableDecimal::INFINITY"
    ##### INFINITY
    value
    :   'INF'

!!! signature constant "MutableDecimal::NEG_INFINITY"
    ##### NEG_INFINITY
    value
    :   '-INF'



## Methods


### Constructor

!!! signature "public Decimal->__construct($value, $scale, $base, bool $baseTenInput)"
    ##### __construct
    **$value**

    description
    :   *No description available*

    **$scale**

    description
    :   *No description available*

    **$base**

    description
    :   *No description available*

    **$baseTenInput**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*
    
---



### Instanced Methods

!!! signature "public MutableDecimal->continuousModulo($mod)"
    ##### continuousModulo
    **$mod**

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---



### Inherited Methods

!!! signature "public Decimal->modulo($mod)"
    ##### modulo
    **$mod**

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->getBase()"
    ##### getBase
    **return**

    type
    :   int

    description
    :   *No description available*

    ###### getBase() Description:

    Returns the current base that the value is in.
    
---

!!! signature "public Decimal->getAsBaseTenRealNumber()"
    ##### getAsBaseTenRealNumber
    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public Decimal->getValue($base)"
    ##### getValue
    **$base**

    description
    :   *No description available*

    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public Decimal->compare(NumberInterface|int|float|string $value)"
    ##### compare
    **$value**

    type
    :   NumberInterface|int|float|string

    description
    :   *No description available*

    **return**

    type
    :   int

    description
    :   *No description available*

    ###### compare() Description:

    Returns the sort compare integer (-1, 0, 1) for the two numbers.
    
---

!!! signature "public Decimal->convertToBase($base)"
    ##### convertToBase
    **$base**

    description
    :   *No description available*

    **return**

    type
    :   NumberInterface

    description
    :   *No description available*

    ###### convertToBase() Description:

    Converts the object to a different base.
    
---

!!! signature "public Decimal->abs()"
    ##### abs
    **return**

    type
    :   DecimalInterface|NumberInterface

    description
    :   *No description available*

    ###### abs() Description:

    Returns the current object as the absolute value of itself.
    
---

!!! signature "public Decimal->absValue()"
    ##### absValue
    **return**

    type
    :   string

    description
    :   *No description available*

    ###### absValue() Description:

    Returns the string of the absolute value of the current object.
    
---

!!! signature "public Decimal->isComplex()"
    ##### isComplex
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public Decimal->__toString()"
    ##### __toString
    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public Number->setMode(int $mode)"
    ##### setMode
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

    ###### setMode() Description:

    Allows you to set a mode on a number to select the calculation methods.
    
     MODE_PRECISION: Use what is necessary to provide an answer that is accurate to the scale setting. MODE_NATIVE: Use built-in functions to perform the math, and accept whatever rounding or truncation this might cause.
    
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
    :   Samsara\Fermat\Types\ComplexNumber

    description
    :   *No description available*
    
---

!!! signature "public Decimal->add($num)"
    ##### add
    **$num**

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*
    
---

!!! signature "public Decimal->subtract($num)"
    ##### subtract
    **$num**

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*
    
---

!!! signature "public Decimal->multiply($num)"
    ##### multiply
    **$num**

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*
    
---

!!! signature "public Decimal->divide($num, ?int $scale)"
    ##### divide
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

!!! signature "public Decimal->pow($num)"
    ##### pow
    **$num**

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*
    
---

!!! signature "public Decimal->sqrt(?int $scale)"
    ##### sqrt
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

!!! signature "public Decimal->isEqual($value)"
    ##### isEqual
    **$value**

    description
    :   *No description available*

    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public Decimal->getScale()"
    ##### getScale
    **return**

    type
    :   ?int

    description
    :   *No description available*
    
---

!!! signature "public Decimal->isGreaterThan($value)"
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

!!! signature "public Decimal->isLessThan($value)"
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

!!! signature "public Decimal->isGreaterThanOrEqualTo($value)"
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

!!! signature "public Decimal->isLessThanOrEqualTo($value)"
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

!!! signature "public Decimal->isNegative()"
    ##### isNegative
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public Decimal->isPositive()"
    ##### isPositive
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public Decimal->isNatural()"
    ##### isNatural
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public Decimal->isWhole()"
    ##### isWhole
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public Decimal->isInt()"
    ##### isInt
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public Decimal->factorial()"
    ##### factorial
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->subFactorial()"
    ##### subFactorial
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->doubleFactorial()"
    ##### doubleFactorial
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->semiFactorial()"
    ##### semiFactorial
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->getLeastCommonMultiple($num)"
    ##### getLeastCommonMultiple
    **$num**

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->getGreatestCommonDivisor($num)"
    ##### getGreatestCommonDivisor
    **$num**

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->isPrime()"
    ##### isPrime
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isPrime() Description:

    This function is a PHP implementation of the function described at: http://stackoverflow.com/a/1801446
    
     It is relatively simple to understand, which is why it was chosen as the implementation. However in the future, an implementation that is based on ECPP (such as the Goldwasser implementation) may be employed to improve speed.
    
---

!!! signature "public Decimal->asPrimeFactors()"
    ##### asPrimeFactors
    **return**

    type
    :   Samsara\Fermat\Types\NumberCollection

    description
    :   *No description available*
    
---

!!! signature "public Decimal->sin(?int $scale, bool $round)"
    ##### sin
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **$round**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->cos(?int $scale, bool $round)"
    ##### cos
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **$round**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->tan(?int $scale, bool $round)"
    ##### tan
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **$round**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->cot(?int $scale, bool $round)"
    ##### cot
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **$round**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->sec(?int $scale, bool $round)"
    ##### sec
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **$round**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->csc(?int $scale, bool $round)"
    ##### csc
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **$round**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->sinh(?int $scale, bool $round)"
    ##### sinh
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **$round**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->cosh(?int $scale, bool $round)"
    ##### cosh
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **$round**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->tanh(?int $scale, bool $round)"
    ##### tanh
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **$round**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->coth(?int $scale, bool $round)"
    ##### coth
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **$round**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->sech(?int $scale, bool $round)"
    ##### sech
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **$round**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->csch(?int $scale, bool $round)"
    ##### csch
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **$round**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->arcsin(?int $scale, bool $round)"
    ##### arcsin
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **$round**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->arccos(?int $scale, bool $round)"
    ##### arccos
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **$round**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->arctan(?int $scale, bool $round)"
    ##### arctan
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **$round**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->arccot(?int $scale, bool $round)"
    ##### arccot
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **$round**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->arcsec(?int $scale, bool $round)"
    ##### arcsec
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **$round**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->arccsc(?int $scale, bool $round)"
    ##### arccsc
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **$round**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->roundToScale(int $scale, ?int $mode)"
    ##### roundToScale
    **$scale**

    type
    :   int

    description
    :   *No description available*

    **$mode**

    type
    :   ?int

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->truncateToScale($scale)"
    ##### truncateToScale
    **$scale**

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->exp(?int $scale, bool $round)"
    ##### exp
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **$round**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->ln(int|null $scale, bool $round)"
    ##### ln
    **$scale**

    type
    :   int|null

    description
    :   The number of digits which should be accurate
    
    

    **$round**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->log10(int|null $scale, bool $round)"
    ##### log10
    **$scale**

    type
    :   int|null

    description
    :   *No description available*

    **$round**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->round(int $decimals, ?int $mode)"
    ##### round
    **$decimals**

    type
    :   int

    description
    :   *No description available*

    **$mode**

    type
    :   ?int

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->truncate(int $decimals)"
    ##### truncate
    **$decimals**

    type
    :   int

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->ceil()"
    ##### ceil
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->floor()"
    ##### floor
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public Decimal->numberOfLeadingZeros()"
    ##### numberOfLeadingZeros
    **return**

    type
    :   int

    description
    :   *No description available*

    ###### numberOfLeadingZeros() Description:

    The number of digits between the radix and the for non-zero digit in the decimal part.
    
---

!!! signature "public Decimal->numberOfTotalDigits()"
    ##### numberOfTotalDigits
    **return**

    type
    :   int

    description
    :   *No description available*

    ###### numberOfTotalDigits() Description:

    The number of digits (excludes the radix).
    
---

!!! signature "public Decimal->numberOfIntDigits()"
    ##### numberOfIntDigits
    **return**

    type
    :   int

    description
    :   *No description available*

    ###### numberOfIntDigits() Description:

    The number of digits in the integer part.
    
---

!!! signature "public Decimal->numberOfDecimalDigits()"
    ##### numberOfDecimalDigits
    **return**

    type
    :   int

    description
    :   *No description available*

    ###### numberOfDecimalDigits() Description:

    The number of digits in the decimal part.
    
---

!!! signature "public Decimal->numberOfSigDecimalDigits()"
    ##### numberOfSigDecimalDigits
    **return**

    type
    :   int

    description
    :   *No description available*

    ###### numberOfSigDecimalDigits() Description:

    The number of digits in the decimal part, excluding leading zeros.
    
---

!!! signature "public Decimal->asInt()"
    ##### asInt
    **return**

    type
    :   int

    description
    :   *No description available*

    ###### asInt() Description:

    Returns the current value as an integer if it is within the max a min int values on the current system. Uses the intval() function to convert the string to an integer type.
    
---

!!! signature "public Decimal->isFloat()"
    ##### isFloat
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public Decimal->asFloat()"
    ##### asFloat
    **return**

    type
    :   float

    description
    :   *No description available*
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."