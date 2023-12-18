# Samsara\Fermat\Core\Values > MutableDecimal

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Core\Types\Decimal


### Implements

!!! signature interface "Hashable"
    ##### Hashable
    namespace
    :   Ds

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

!!! signature "public Decimal->__construct(Decimal|string|int|float $value, int|null $scale, NumberBase $base, bool $baseTenInput)"
    ##### __construct
    **$value**

    type
    :   Decimal|string|int|float

    description
    :   The value to create this number with. Integers and floats are used as real numbers.

    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from math operations. Leave null to autodetect based on input.

    **$base**

    type
    :   NumberBase

    description
    :   The base you want this number to have any time the value is retrieved.

    **$baseTenInput**

    type
    :   bool

    description
    :   If true, the $value argument will be treated as base 10 regardless of $base. If false, the $value will be interpreted as being in $base.
    
    

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*
    
---



### Instanced Methods

!!! signature "public MutableDecimal->continuousModulo(Decimal|string|int|float $mod)"
    ##### continuousModulo
    **$mod**

    type
    :   Decimal|string|int|float

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*
    
---



### Inherited Static Methods

!!! signature "public Decimal::createFromFormat(NumberFormat $format, NumberGrouping $grouping, string $value, int|null $scale, NumberBase $base, bool $baseTenInput)"
    ##### createFromFormat
    **$format**

    type
    :   NumberFormat

    description
    :   *No description available*

    **$grouping**

    type
    :   NumberGrouping

    description
    :   *No description available*

    **$value**

    type
    :   string

    description
    :   *No description available*

    **$scale**

    type
    :   int|null

    description
    :   *No description available*

    **$base**

    type
    :   NumberBase

    description
    :   *No description available*

    **$baseTenInput**

    type
    :   bool

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*

    ###### createFromFormat() Description:

    Creates an instance of this class from a number string that has been formatted by the Fermat formatter.

---



### Inherited Methods

!!! signature "public Decimal->getAsBaseTenRealNumber()"
    ##### getAsBaseTenRealNumber
    **return**

    type
    :   string

    description
    :   *No description available*

    ###### getAsBaseTenRealNumber() Description:

    Returns the current value as a string in base 10, converted to a real number. If the number is imaginary, the i is simply not printed. If the number is complex, then the absolute value is returned.
    
---

!!! signature "public Decimal->getValue(NumberBase|null $base)"
    ##### getValue
    **$base**

    type
    :   NumberBase|null

    description
    :   If provided, will return the value in the provided base, regardless of the object's base setting.
    
    

    **return**

    type
    :   string

    description
    :   *No description available*

    ###### getValue() Description:

    Returns the current value as a string.
    
---

!!! signature "public Decimal->setBase(NumberBase $base)"
    ##### setBase
    **$base**

    type
    :   NumberBase

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*

    ###### setBase() Description:

    Changes the base setting for this number.
    
---

!!! signature "public Decimal->isComplex()"
    ##### isComplex
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isComplex() Description:

    Returns true if the number is complex, false if the number is real or imaginary.
    
---

!!! signature "public Decimal->asComplex()"
    ##### asComplex
    **return**

    type
    :   Samsara\Fermat\Complex\Values\ImmutableComplexNumber

    description
    :   *No description available*
    
---

!!! signature "public Decimal->asImaginary()"
    ##### asImaginary
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    ###### asImaginary() Description:

    Returns a new instance of this object with a base ten imaginary number.
    
---

!!! signature "public Decimal->asReal()"
    ##### asReal
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    ###### asReal() Description:

    Returns a new instance of this object with a base ten real number.
    
---

!!! signature "public Decimal->abs()"
    ##### abs
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Core\Values\MutableDecimal

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

!!! signature "public Decimal->compare(Number|int|float|string $value)"
    ##### compare
    **$value**

    type
    :   Number|int|float|string

    description
    :   
    
    

    **return**

    type
    :   int

    description
    :   *No description available*

    ###### compare() Description:

    Returns the sort compare integer (signum) (-1, 0, 1) for the two numbers.
    
---

!!! signature "public Decimal->modulo($mod)"
    ##### modulo
    **$mod**

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public Number->getBase()"
    ##### getBase
    **return**

    type
    :   Samsara\Fermat\Core\Enums\NumberBase

    description
    :   *No description available*

    ###### getBase() Description:

    Returns the current base that the value is in.
    
---

!!! signature "public Number->isImaginary()"
    ##### isImaginary
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isImaginary() Description:

    This function returns true if the number is imaginary, and false if the number is real or complex
    
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

!!! signature "public Number->equals(mixed $obj)"
    ##### equals
    **$obj**

    type
    :   mixed

    description
    :   
    
    

    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### equals() Description:

    Implemented to satisfy Hashable implementation
    
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

!!! signature "public Number->__toString()"
    ##### __toString
    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public Decimal->isEqual(Number|int|string|float $value)"
    ##### isEqual
    **$value**

    type
    :   Number|int|string|float

    description
    :   The value to compare against
    
    

    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isEqual() Description:

    Compares this number to another number and returns whether or not they are equal.
    
---

!!! signature "public Decimal->isGreaterThan(Number|int|string|float $value)"
    ##### isGreaterThan
    **$value**

    type
    :   Number|int|string|float

    description
    :   The value to compare against
    
    

    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isGreaterThan() Description:

    Compares this number to another number and returns true if this number is closer to positive infinity.
    
---

!!! signature "public Decimal->isGreaterThanOrEqualTo(Number|int|string|float $value)"
    ##### isGreaterThanOrEqualTo
    **$value**

    type
    :   Number|int|string|float

    description
    :   The value to compare against
    
    

    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isGreaterThanOrEqualTo() Description:

    Compares this number to another number and returns true if this number is closer to positive infinity or equal.
    
---

!!! signature "public Decimal->isLessThan(Number|int|string|float $value)"
    ##### isLessThan
    **$value**

    type
    :   Number|int|string|float

    description
    :   The value to compare against
    
    

    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isLessThan() Description:

    Compares this number to another number and returns true if this number is closer to negative infinity.
    
---

!!! signature "public Decimal->isLessThanOrEqualTo(Number|int|string|float $value)"
    ##### isLessThanOrEqualTo
    **$value**

    type
    :   Number|int|string|float

    description
    :   The value to compare against
    
    

    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isLessThanOrEqualTo() Description:

    Compares this number to another number and returns true if this number is closer to negative infinity or equal.
    
---

!!! signature "public Number->getMode()"
    ##### getMode
    **return**

    type
    :   ?Samsara\Fermat\Core\Enums\CalcMode

    description
    :   *No description available*

    ###### getMode() Description:

    Returns the enum setting for this object's calculation mode. If this is null, then the default mode in the CalculationModeProvider at the time a calculation is performed will be used.
    
---

!!! signature "public Number->getResolvedMode()"
    ##### getResolvedMode
    **return**

    type
    :   Samsara\Fermat\Core\Enums\CalcMode

    description
    :   *No description available*

    ###### getResolvedMode() Description:

    Returns the mode that this object would use at the moment, accounting for all values and defaults.
    
---

!!! signature "public Number->setMode(CalcMode|null $mode)"
    ##### setMode
    **$mode**

    type
    :   CalcMode|null

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*

    ###### setMode() Description:

    Allows you to set a mode on a number to select the calculation methods. If this is null, then the default mode in the CalculationModeProvider at the time a calculation is performed will be used.
    
---

!!! signature "public Decimal->add(string|int|float|Decimal|Fraction|ComplexNumber $num)"
    ##### add
    **$num**

    type
    :   string|int|float|Decimal|Fraction|ComplexNumber

    description
    :   The number you are adding to this number
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\MutableDecimal|Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Complex\Values\MutableComplexNumber|Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Core\Values\MutableFraction|Samsara\Fermat\Core\Values\ImmutableFraction|static

    description
    :   *No description available*

    ###### add() Description:

    Adds a number to this number. Works (to the degree that math allows it to work) for all classes that extend the Number abstract class.
    
---

!!! signature "public Decimal->divide(string|int|float|Decimal|Fraction|ComplexNumber $num, int|null $scale)"
    ##### divide
    **$num**

    type
    :   string|int|float|Decimal|Fraction|ComplexNumber

    description
    :   The number you dividing this number by

    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Core\Values\ImmutableFraction|static

    description
    :   *No description available*

    ###### divide() Description:

    Divides this number by a number. Works (to the degree that math allows it to work) for all classes that extend the Number abstract class.
    
---

!!! signature "public Decimal->multiply(string|int|float|Decimal|Fraction|ComplexNumber $num)"
    ##### multiply
    **$num**

    type
    :   string|int|float|Decimal|Fraction|ComplexNumber

    description
    :   The number you are multiplying with this number
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\MutableDecimal|Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Complex\Values\MutableComplexNumber|Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Core\Values\MutableFraction|Samsara\Fermat\Core\Values\ImmutableFraction|static

    description
    :   *No description available*

    ###### multiply() Description:

    Multiplies a number with this number. Works (to the degree that math allows it to work) for all classes that extend the Number abstract class.
    
---

!!! signature "public Decimal->pow(string|int|float|Decimal|Fraction|ComplexNumber $num)"
    ##### pow
    **$num**

    type
    :   string|int|float|Decimal|Fraction|ComplexNumber

    description
    :   The exponent to raise the number to
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\MutableDecimal|Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Complex\Values\MutableComplexNumber|Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Core\Values\MutableFraction|Samsara\Fermat\Core\Values\ImmutableFraction|static

    description
    :   *No description available*

    ###### pow() Description:

    Raises this number to the power of a number. Works (to the degree that math allows it to work) for all classes that extend the Number abstract class.
    
---

!!! signature "public Decimal->sqrt(int|null $scale)"
    ##### sqrt
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the operation. Leave null to use this object's scale.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\MutableDecimal|Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Complex\Values\MutableComplexNumber|Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Core\Values\MutableFraction|Samsara\Fermat\Core\Values\ImmutableFraction|static

    description
    :   *No description available*

    ###### sqrt() Description:

    Takes the (positive) square root of this number. Works (to the degree that math allows it to work) for all classes that extend the Number abstract class.
    
---

!!! signature "public Decimal->subtract(string|int|float|Decimal|Fraction|ComplexNumber $num)"
    ##### subtract
    **$num**

    type
    :   string|int|float|Decimal|Fraction|ComplexNumber

    description
    :   The number you are subtracting from this number
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\MutableDecimal|Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Complex\Values\MutableComplexNumber|Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Core\Values\MutableFraction|Samsara\Fermat\Core\Values\ImmutableFraction|static

    description
    :   *No description available*

    ###### subtract() Description:

    Subtracts a number from this number. Works (to the degree that math allows it to work) for all classes that extend the Number abstract class.
    
---

!!! signature "public Decimal->isInt()"
    ##### isInt
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isInt() Description:

    Returns true if this number has no non-zero digits in the decimal part.
    
---

!!! signature "public Decimal->isNatural()"
    ##### isNatural
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isNatural() Description:

    Alias for isInt(). Returns true if this number has no non-zero digits in the decimal part.
    
---

!!! signature "public Decimal->isNegative()"
    ##### isNegative
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isNegative() Description:

    Returns true if this number is less than zero
    
---

!!! signature "public Decimal->isPositive()"
    ##### isPositive
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isPositive() Description:

    Returns true if this number is larger than zero
    
---

!!! signature "public Decimal->isWhole()"
    ##### isWhole
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isWhole() Description:

    Alias for isInt(). Returns true if this number has no non-zero digits in the decimal part.
    
---

!!! signature "public Decimal->getDivisors()"
    ##### getDivisors
    **return**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*

    ###### getDivisors() Description:

    Only valid for integer numbers. Returns a collection of all the integer divisors of this number.
    
---

!!! signature "public Decimal->getGreatestCommonDivisor($num)"
    ##### getGreatestCommonDivisor
    **$num**

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*

    ###### getGreatestCommonDivisor() Description:

    Only valid for integer numbers. Returns the greatest common divisor for this number and the supplied number.
    
---

!!! signature "public Decimal->getLeastCommonMultiple($num)"
    ##### getLeastCommonMultiple
    **$num**

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*

    ###### getLeastCommonMultiple() Description:

    Only valid for integer numbers. Returns the least common multiple of this number and the supplied number.
    
---

!!! signature "public Decimal->getPrimeFactors()"
    ##### getPrimeFactors
    **return**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*
    
---

!!! signature "public Decimal->isPrime(int|null $certainty)"
    ##### isPrime
    **$certainty**

    type
    :   int|null

    description
    :   The certainty level desired. False positive rate = 1 in 4^$certainty.
    
    

    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isPrime() Description:

    Only valid for integer numbers. Uses the Miller-Rabin probabilistic primality test. The default "certainty" value of 20 results in a false-positive rate of 1 in 1.10 x 10^12.
    
     With high enough certainty values, the probability that the program returned an incorrect result due to errors in the computer hardware begins to dominate. Typically, a certainty of around 40 is sufficient for a prime number used in a cryptographic context.
    
---

!!! signature "public Decimal->doubleFactorial()"
    ##### doubleFactorial
    **return**

    type
    :   static

    description
    :   *No description available*

    ###### doubleFactorial() Description:

    Only valid for integer numbers. Takes the double factorial of this number. Not to be confused with taking the factorial twice which is (n!)!, the double factorial n!! multiplies all the numbers between 1 and n that share the same parity (odd or even).
    
     For more information, see: https://mathworld.wolfram.com/DoubleFactorial.html
    
---

!!! signature "public Decimal->factorial()"
    ##### factorial
    **return**

    type
    :   static

    description
    :   *No description available*

    ###### factorial() Description:

    Only valid for integer numbers. Takes the factorial of this number. The factorial is every number between 1 and this number multiplied together.
    
---

!!! signature "public Decimal->fallingFactorial(Samsara\Fermat\Core\Types\Decimal|string|int|float $num)"
    ##### fallingFactorial
    **$num**

    type
    :   Samsara\Fermat\Core\Types\Decimal|string|int|float

    description
    :   *No description available*

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public Decimal->risingFactorial(Samsara\Fermat\Core\Types\Decimal|string|int|float $num)"
    ##### risingFactorial
    **$num**

    type
    :   Samsara\Fermat\Core\Types\Decimal|string|int|float

    description
    :   *No description available*

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public Decimal->semiFactorial()"
    ##### semiFactorial
    **return**

    type
    :   static

    description
    :   *No description available*

    ###### semiFactorial() Description:

    Alias for doubleFactorial().
    
---

!!! signature "public Decimal->subFactorial()"
    ##### subFactorial
    **return**

    type
    :   static

    description
    :   *No description available*

    ###### subFactorial() Description:

    Only valid for integer numbers. Takes the subfactorial of this number. The subfactorial is the number of derangements of a set with n members.
    
     For more information, see: https://mathworld.wolfram.com/Subfactorial.html
    
---

!!! signature "public Decimal->cos(int|null $scale, bool $round)"
    ##### cos
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.

    **$round**

    type
    :   bool

    description
    :   If true, use the current rounding mode to round the result. If false, truncate the result.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### cos() Description:

    Returns the cosine of this number.
    
---

!!! signature "public Decimal->cosh(int|null $scale, bool $round)"
    ##### cosh
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.

    **$round**

    type
    :   bool

    description
    :   If true, use the current rounding mode to round the result. If false, truncate the result.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### cosh() Description:

    Returns the hyperbolic cosine of this number.
    
---

!!! signature "public Decimal->cot(int|null $scale, bool $round)"
    ##### cot
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.

    **$round**

    type
    :   bool

    description
    :   If true, use the current rounding mode to round the result. If false, truncate the result.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### cot() Description:

    Returns the cotangent of this number.
    
---

!!! signature "public Decimal->coth(int|null $scale, bool $round)"
    ##### coth
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.

    **$round**

    type
    :   bool

    description
    :   If true, use the current rounding mode to round the result. If false, truncate the result.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### coth() Description:

    Returns the hyperbolic cotangent of this number.
    
---

!!! signature "public Decimal->csc(int|null $scale, bool $round)"
    ##### csc
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.

    **$round**

    type
    :   bool

    description
    :   If true, use the current rounding mode to round the result. If false, truncate the result.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### csc() Description:

    Returns the cosecant of this number.
    
---

!!! signature "public Decimal->csch(int|null $scale, bool $round)"
    ##### csch
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.

    **$round**

    type
    :   bool

    description
    :   If true, use the current rounding mode to round the result. If false, truncate the result.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### csch() Description:

    Returns the hyperbolic cosecant of this number.
    
---

!!! signature "public Decimal->sec(int|null $scale, bool $round)"
    ##### sec
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.

    **$round**

    type
    :   bool

    description
    :   If true, use the current rounding mode to round the result. If false, truncate the result.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### sec() Description:

    Returns the secant of this number.
    
---

!!! signature "public Decimal->sech(int|null $scale, bool $round)"
    ##### sech
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.

    **$round**

    type
    :   bool

    description
    :   If true, use the current rounding mode to round the result. If false, truncate the result.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### sech() Description:

    Returns the hyperbolic secant of this number.
    
---

!!! signature "public Decimal->sin(int|null $scale, bool $round)"
    ##### sin
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.

    **$round**

    type
    :   bool

    description
    :   If true, use the current rounding mode to round the result. If false, truncate the result.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### sin() Description:

    Returns the sine of this number.
    
---

!!! signature "public Decimal->sinh(int|null $scale, bool $round)"
    ##### sinh
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.

    **$round**

    type
    :   bool

    description
    :   If true, use the current rounding mode to round the result. If false, truncate the result.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### sinh() Description:

    Returns the hyperbolic sine of this number.
    
---

!!! signature "public Decimal->tan(int|null $scale, bool $round)"
    ##### tan
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.

    **$round**

    type
    :   bool

    description
    :   If true, use the current rounding mode to round the result. If false, truncate the result.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### tan() Description:

    Returns the tangent of this number.
    
---

!!! signature "public Decimal->tanh(int|null $scale, bool $round)"
    ##### tanh
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.

    **$round**

    type
    :   bool

    description
    :   If true, use the current rounding mode to round the result. If false, truncate the result.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### tanh() Description:

    Returns the hyperbolic tangent of this number.
    
---

!!! signature "public Decimal->arccos(int|null $scale, bool $round)"
    ##### arccos
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.

    **$round**

    type
    :   bool

    description
    :   If true, use the current rounding mode to round the result. If false, truncate the result.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### arccos() Description:

    Returns the inverse cosine of this number.
    
---

!!! signature "public Decimal->arccot(int|null $scale, bool $round)"
    ##### arccot
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.

    **$round**

    type
    :   bool

    description
    :   If true, use the current rounding mode to round the result. If false, truncate the result.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### arccot() Description:

    Returns the inverse cotangent of this number.
    
---

!!! signature "public Decimal->arccsc(int|null $scale, bool $round)"
    ##### arccsc
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.

    **$round**

    type
    :   bool

    description
    :   If true, use the current rounding mode to round the result. If false, truncate the result.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### arccsc() Description:

    Returns the inverse cosecant of this number.
    
---

!!! signature "public Decimal->arcsec(int|null $scale, bool $round)"
    ##### arcsec
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.

    **$round**

    type
    :   bool

    description
    :   If true, use the current rounding mode to round the result. If false, truncate the result.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### arcsec() Description:

    Returns the inverse secant of this number.
    
---

!!! signature "public Decimal->arcsin(int|null $scale, bool $round)"
    ##### arcsin
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.

    **$round**

    type
    :   bool

    description
    :   If true, use the current rounding mode to round the result. If false, truncate the result.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### arcsin() Description:

    Returns the inverse sine of this number.
    
---

!!! signature "public Decimal->arctan(int|null $scale, bool $round)"
    ##### arctan
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.

    **$round**

    type
    :   bool

    description
    :   If true, use the current rounding mode to round the result. If false, truncate the result.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### arctan() Description:

    Returns the inverse tangent of this number.
    
---

!!! signature "public Decimal->getScale()"
    ##### getScale
    **return**

    type
    :   int

    description
    :   *No description available*

    ###### getScale() Description:

    Gets this number's setting for the number of decimal places it will calculate accurately based on the inputs.
    
     Multiple operations, each rounding or truncating digits, will increase the error and reduce the actual accuracy of the result.
    
---

!!! signature "public Decimal->roundToScale(int $scale, RoundingMode|null $mode)"
    ##### roundToScale
    **$scale**

    type
    :   int

    description
    :   The number of decimal places to round to.

    **$mode**

    type
    :   RoundingMode|null

    description
    :   The rounding mode to use for this operation. If null, will use the current default mode.
    
    

    **return**

    type
    :   static

    description
    :   *No description available*

    ###### roundToScale() Description:

    Round this number's value to the given number of decimal places, and set this number's scale to that many digits.
    
---

!!! signature "public Decimal->truncateToScale(int $scale)"
    ##### truncateToScale
    **$scale**

    type
    :   int

    description
    :   The number of decimal places to truncate to.
    
    

    **return**

    type
    :   static

    description
    :   *No description available*

    ###### truncateToScale() Description:

    Truncate this number's value to the given number of decimal places, and set this number's scale to that many digits.
    
---

!!! signature "public Decimal->exp(int|null $scale, bool $round)"
    ##### exp
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.

    **$round**

    type
    :   bool

    description
    :   If true, use the current rounding mode to round the result. If false, truncate the result.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### exp() Description:

    Returns the result of e^this
    
---

!!! signature "public Decimal->ln(int|null $scale, bool $round)"
    ##### ln
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.

    **$round**

    type
    :   bool

    description
    :   If true, use the current rounding mode to round the result. If false, truncate the result.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### ln() Description:

    Returns the natural log of this number. The natural log is the inverse of the exp() function.
    
---

!!! signature "public Decimal->log10(int|null $scale, bool $round)"
    ##### log10
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.

    **$round**

    type
    :   bool

    description
    :   If true, use the current rounding mode to round the result. If false, truncate the result.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### log10() Description:

    Returns the log base 10 of this number.
    
---

!!! signature "public Decimal->getDecimalPart()"
    ##### getDecimalPart
    **return**

    type
    :   string

    description
    :   *No description available*

    ###### getDecimalPart() Description:

    Returns only the decimal part of the number as a string.
    
---

!!! signature "public Decimal->getWholePart()"
    ##### getWholePart
    **return**

    type
    :   string

    description
    :   *No description available*

    ###### getWholePart() Description:

    Returns only the integer part of the number as a string.
    
---

!!! signature "public Decimal->isFloat()"
    ##### isFloat
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isFloat() Description:

    Returns true if any non-zero digits exist in the decimal part.
    
---

!!! signature "public Decimal->asFloat()"
    ##### asFloat
    **return**

    type
    :   float

    description
    :   *No description available*

    ###### asFloat() Description:

    Returns the current value as a float if it is within the max and min float values on the current system. Uses the float) explicit cast to convert the string to a float type.
    
---

!!! signature "public Decimal->asInt()"
    ##### asInt
    **return**

    type
    :   int

    description
    :   *No description available*

    ###### asInt() Description:

    Returns the current value as an integer if it is within the max and min int values on the current system. Uses the intval() function to convert the string to an integer type.
    
---

!!! signature "public Decimal->ceil()"
    ##### ceil
    **return**

    type
    :   static

    description
    :   *No description available*

    ###### ceil() Description:

    Round to the next integer closest to positive infinity.
    
---

!!! signature "public Decimal->floor()"
    ##### floor
    **return**

    type
    :   static

    description
    :   *No description available*

    ###### floor() Description:

    Round to the next integer closest to negative infinity.
    
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

!!! signature "public Decimal->numberOfLeadingZeros()"
    ##### numberOfLeadingZeros
    **return**

    type
    :   int

    description
    :   *No description available*

    ###### numberOfLeadingZeros() Description:

    The number of digits between the radix and the first non-zero digit in the decimal part.
    
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

!!! signature "public Decimal->round(int $decimals, RoundingMode|null $mode)"
    ##### round
    **$decimals**

    type
    :   int

    description
    :   The number of decimal places to round to. Negative values round that many integer digits.

    **$mode**

    type
    :   RoundingMode|null

    description
    :   The rounding mode to use for this operation. If null, will use the current default mode.
    
    

    **return**

    type
    :   static

    description
    :   *No description available*

    ###### round() Description:

    Round this number's value to the given number of decimal places, but keep the current scale setting of this number.
    
     NOTE: Rounding to a negative number of digits will round the integer part of the number.
    
---

!!! signature "public Decimal->truncate(int $decimals)"
    ##### truncate
    **$decimals**

    type
    :   int

    description
    :   The number of decimal places to truncate to.
    
    

    **return**

    type
    :   static

    description
    :   *No description available*

    ###### truncate() Description:

    Truncate this number's value to the given number of decimal places, but keep the current scale setting of this number.
    
---

!!! signature "public Decimal->getCurrencyValue(Currency $currency)"
    ##### getCurrencyValue
    **$currency**

    type
    :   Currency

    description
    :   The currency you want this number to appear in.
    
    

    **return**

    type
    :   string

    description
    :   *No description available*

    ###### getCurrencyValue() Description:

    Returns a formatting string according to this number's current settings as a currency.
    
---

!!! signature "public Decimal->getFormat()"
    ##### getFormat
    **return**

    type
    :   Samsara\Fermat\Core\Enums\NumberFormat

    description
    :   *No description available*

    ###### getFormat() Description:

    Gets the current format setting of this number.
    
---

!!! signature "public Decimal->setFormat(NumberFormat $format)"
    ##### setFormat
    **$format**

    type
    :   NumberFormat

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*

    ###### setFormat() Description:

    Sets the format of this number for when a format export function is used.
    
---

!!! signature "public Decimal->getFormattedValue(NumberBase|null $base)"
    ##### getFormattedValue
    **$base**

    type
    :   NumberBase|null

    description
    :   The base you want the formatted number to be in.
    
    

    **return**

    type
    :   string

    description
    :   *No description available*

    ###### getFormattedValue() Description:

    Returns the current value formatted according to the settings in getGrouping() and getFormat()
    
---

!!! signature "public Decimal->getGrouping()"
    ##### getGrouping
    **return**

    type
    :   Samsara\Fermat\Core\Enums\NumberGrouping

    description
    :   *No description available*

    ###### getGrouping() Description:

    Gets the current number grouping setting of this number.
    
---

!!! signature "public Decimal->setGrouping(NumberGrouping $grouping)"
    ##### setGrouping
    **$grouping**

    type
    :   NumberGrouping

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*

    ###### setGrouping() Description:

    Sets the number grouping of this number for when a format export function is used.
    
---

!!! signature "public Decimal->getScientificValue(int|null $scale)"
    ##### getScientificValue
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.
    
    

    **return**

    type
    :   string

    description
    :   *No description available*

    ###### getScientificValue() Description:

    Returns the current value in scientific notation compatible with the way PHP coerces float values into strings.
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."