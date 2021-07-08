# Samsara\Fermat\Values > MutableDecimal

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Types\Decimal


### Implements

!!! signature interface "SimpleNumberInterface"
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers

    description
    :   *No description available*

!!! signature interface "BaseConversionInterface"
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Characteristics

    description
    :   *No description available*

!!! signature interface "DecimalInterface"
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

!!! signature constant "MutableDecimal::INFINITY"
    value
    :   'INF'

!!! signature constant "MutableDecimal::NEG_INFINITY"
    value
    :   '-INF'



## Methods


### Constructor

!!! signature "public Decimal->__construct($value, $scale, $base, bool $baseTenInput)"
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
    **return**

    type
    :   int

    description
    :   *No description available*

    **Decimal->getBase Description**

    Returns the current base that the value is in.

---

!!! signature "public Decimal->getAsBaseTenRealNumber()"
    **return**

    type
    :   string

    description
    :   *No description available*

---

!!! signature "public Decimal->getValue($base)"
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

    **Decimal->compare Description**

    Returns the sort compare integer (-1, 0, 1) for the two numbers.

---

!!! signature "public Decimal->convertToBase($base)"
    **$base**

    description
    :   *No description available*

    **return**

    type
    :   NumberInterface

    description
    :   *No description available*

    **Decimal->convertToBase Description**

    Converts the object to a different base.

---

!!! signature "public Decimal->abs()"
    **return**

    type
    :   DecimalInterface|NumberInterface

    description
    :   *No description available*

    **Decimal->abs Description**

    Returns the current object as the absolute value of itself.

---

!!! signature "public Decimal->absValue()"
    **return**

    type
    :   string

    description
    :   *No description available*

    **Decimal->absValue Description**

    Returns the string of the absolute value of the current object.

---

!!! signature "public Decimal->isComplex()"
    **return**

    type
    :   bool

    description
    :   *No description available*

---

!!! signature "public Decimal->__toString()"
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

!!! signature "public Decimal->add($num)"
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
    **return**

    type
    :   ?int

    description
    :   *No description available*

---

!!! signature "public Decimal->isGreaterThan($value)"
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
    **return**

    type
    :   bool

    description
    :   *No description available*

---

!!! signature "public Decimal->isPositive()"
    **return**

    type
    :   bool

    description
    :   *No description available*

---

!!! signature "public Decimal->isNatural()"
    **return**

    type
    :   bool

    description
    :   *No description available*

---

!!! signature "public Decimal->isWhole()"
    **return**

    type
    :   bool

    description
    :   *No description available*

---

!!! signature "public Decimal->isInt()"
    **return**

    type
    :   bool

    description
    :   *No description available*

---

!!! signature "public Decimal->factorial()"
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*

---

!!! signature "public Decimal->subFactorial()"
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*

---

!!! signature "public Decimal->doubleFactorial()"
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*

---

!!! signature "public Decimal->semiFactorial()"
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*

---

!!! signature "public Decimal->getLeastCommonMultiple($num)"
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
    **return**

    type
    :   bool

    description
    :   *No description available*

    **Decimal->isPrime Description**

    This function is a PHP implementation of the function described at: http://stackoverflow.com/a/1801446
    
     It is relatively simple to understand, which is why it was chosen as the implementation. However in the future, an implementation that is based on ECPP (such as the Goldwasser implementation) may be employed to improve speed.

---

!!! signature "public Decimal->asPrimeFactors()"
    **return**

    type
    :   Samsara\Fermat\Types\NumberCollection

    description
    :   *No description available*

---

!!! signature "public Decimal->sin(?int $scale, bool $round)"
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
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*

---

!!! signature "public Decimal->floor()"
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*

---

!!! signature "public Decimal->numberOfLeadingZeros()"
    **return**

    type
    :   int

    description
    :   *No description available*

    **Decimal->numberOfLeadingZeros Description**

    The number of digits between the radix and the for non-zero digit in the decimal part.

---

!!! signature "public Decimal->numberOfTotalDigits()"
    **return**

    type
    :   int

    description
    :   *No description available*

    **Decimal->numberOfTotalDigits Description**

    The number of digits (excludes the radix).

---

!!! signature "public Decimal->numberOfIntDigits()"
    **return**

    type
    :   int

    description
    :   *No description available*

    **Decimal->numberOfIntDigits Description**

    The number of digits in the integer part.

---

!!! signature "public Decimal->numberOfDecimalDigits()"
    **return**

    type
    :   int

    description
    :   *No description available*

    **Decimal->numberOfDecimalDigits Description**

    The number of digits in the decimal part.

---

!!! signature "public Decimal->numberOfSigDecimalDigits()"
    **return**

    type
    :   int

    description
    :   *No description available*

    **Decimal->numberOfSigDecimalDigits Description**

    The number of digits in the decimal part, excluding leading zeros.

---

!!! signature "public Decimal->asInt()"
    **return**

    type
    :   int

    description
    :   *No description available*

    **Decimal->asInt Description**

    Returns the current value as an integer if it is within the max a min int values on the current system. Uses the intval() function to convert the string to an integer type.

---

!!! signature "public Decimal->isFloat()"
    **return**

    type
    :   bool

    description
    :   *No description available*

---

!!! signature "public Decimal->asFloat()"
    **return**

    type
    :   float

    description
    :   *No description available*

---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."