# Samsara\Fermat\Core\Values > ImmutableFraction

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Core\Types\Fraction


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

!!! signature "public Fraction->__construct(Decimal|string|int|float $numerator, Decimal|string|int|float $denominator, NumberBase $base)"
    ##### __construct
    **$numerator**

    type
    :   Decimal|string|int|float

    description
    :   The numerator of the fraction

    **$denominator**

    type
    :   Decimal|string|int|float

    description
    :   The denominator of the fraction

    **$base**

    type
    :   NumberBase

    description
    :   The base you want this number to have any time the value is retrieved.
    
    

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*
    
---



### Inherited Methods

!!! signature "public Fraction->getAsBaseTenRealNumber()"
    ##### getAsBaseTenRealNumber
    **return**

    type
    :   string

    description
    :   *No description available*

    ###### getAsBaseTenRealNumber() Description:

    Returns the current value as a string in base 10, converted to a real number. If the number is imaginary, the i is simply not printed. If the number is complex, then the absolute value is returned.
    
---

!!! signature "public Fraction->getDenominator()"
    ##### getDenominator
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    ###### getDenominator() Description:

    Returns the ImmutableDecimal instance for the denominator
    
---

!!! signature "public Fraction->getFormattedValue()"
    ##### getFormattedValue
    **return**

    type
    :   string

    description
    :   *No description available*

    ###### getFormattedValue() Description:

    Returns the current value formatted according to the settings in getGrouping() and getFormat()
    
---

!!! signature "public Fraction->getGreatestCommonDivisor()"
    ##### getGreatestCommonDivisor
    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    ###### getGreatestCommonDivisor() Description:

    Returns the greatest common divisor for the numerator and denominator.
    
---

!!! signature "public Fraction->getNumerator()"
    ##### getNumerator
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    ###### getNumerator() Description:

    Returns the ImmutableDecimal instance for the numerator
    
---

!!! signature "public Fraction->getNumeratorsWithSameDenominator(Fraction $fraction, Decimal|null $lcm)"
    ##### getNumeratorsWithSameDenominator
    **$fraction**

    type
    :   Fraction

    description
    :   The fraction to compare this fraction against.

    **$lcm**

    type
    :   Decimal|null

    description
    :   The common multiple to use. If left null, will use the least common multiple.
    
    

    **return**

    type
    :   array

    description
    :   *No description available*

    ###### getNumeratorsWithSameDenominator() Description:

    Gets the new numerators for two fractions after they have been converted to have the same denominator. The denominator used is determined by the output of getSmallestCommonDenominator().
    
---

!!! signature "public Fraction->getScale()"
    ##### getScale
    **return**

    type
    :   ?int

    description
    :   *No description available*

    ###### getScale() Description:

    Gets this number's setting for the number of decimal places it will calculate accurately based on the inputs.
    
     Multiple operations, each rounding or truncating digits, will increase the error and reduce the actual accuracy of the result.
    
---

!!! signature "public Fraction->getSmallestCommonDenominator(Fraction $fraction)"
    ##### getSmallestCommonDenominator
    **$fraction**

    type
    :   Fraction

    description
    :   The fraction to compare this fraction against
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    ###### getSmallestCommonDenominator() Description:

    Returns the smallest common denominator between two fractions.
    
---

!!! signature "public Fraction->getValue(NumberBase|null $base)"
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

!!! signature "public Fraction->setMode(CalcMode|null $mode)"
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

!!! signature "public Fraction->isComplex()"
    ##### isComplex
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isComplex() Description:

    Returns true if the number is complex, false if the number is real or imaginary.
    
---

!!! signature "public Fraction->asComplex()"
    ##### asComplex
    **return**

    type
    :   Samsara\Fermat\Complex\Values\ImmutableComplexNumber

    description
    :   *No description available*
    
---

!!! signature "public Fraction->asDecimal(int $scale)"
    ##### asDecimal
    **$scale**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    ###### asDecimal() Description:

    Converts the fraction to an ImmutableDecimal by performing the division that the fraction implies.
    
---

!!! signature "public Fraction->asImaginary()"
    ##### asImaginary
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableFraction

    description
    :   *No description available*

    ###### asImaginary() Description:

    Returns a new instance of this object with a base ten imaginary number.
    
---

!!! signature "public Fraction->asReal()"
    ##### asReal
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableFraction

    description
    :   *No description available*

    ###### asReal() Description:

    Returns a new instance of this object with a base ten real number.
    
---

!!! signature "public Fraction->abs()"
    ##### abs
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableFraction|Samsara\Fermat\Core\Values\MutableFraction|static

    description
    :   *No description available*

    ###### abs() Description:

    Returns the current object as the absolute value of itself.
    
---

!!! signature "public Fraction->absValue()"
    ##### absValue
    **return**

    type
    :   string

    description
    :   *No description available*

    ###### absValue() Description:

    Returns the string of the absolute value of the current object.
    
---

!!! signature "public Fraction->compare(Number|int|float|string $value)"
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

!!! signature "public Fraction->simplify()"
    ##### simplify
    **return**

    type
    :   static

    description
    :   *No description available*

    ###### simplify() Description:

    Simplifies the current fraction to its reduced form.
    
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

!!! signature "public Fraction->isEqual(Number|int|string|float $value)"
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

!!! signature "public Fraction->isGreaterThan(Number|int|string|float $value)"
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

!!! signature "public Fraction->isGreaterThanOrEqualTo(Number|int|string|float $value)"
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

!!! signature "public Fraction->isLessThan(Number|int|string|float $value)"
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

!!! signature "public Fraction->isLessThanOrEqualTo(Number|int|string|float $value)"
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

!!! signature "public Fraction->add(string|int|float|Decimal|Fraction|ComplexNumber $num)"
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

!!! signature "public Fraction->divide(string|int|float|Decimal|Fraction|ComplexNumber $num, int|null $scale)"
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

!!! signature "public Fraction->multiply(string|int|float|Decimal|Fraction|ComplexNumber $num)"
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

!!! signature "public Fraction->pow(string|int|float|Decimal|Fraction|ComplexNumber $num)"
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

!!! signature "public Fraction->sqrt(int|null $scale)"
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

!!! signature "public Fraction->subtract(string|int|float|Decimal|Fraction|ComplexNumber $num)"
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

!!! signature "public Fraction->isInt()"
    ##### isInt
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isInt() Description:

    Returns true if this number has no non-zero digits in the decimal part.
    
---

!!! signature "public Fraction->isNatural()"
    ##### isNatural
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isNatural() Description:

    Alias for isInt(). Returns true if this number has no non-zero digits in the decimal part.
    
---

!!! signature "public Fraction->isNegative()"
    ##### isNegative
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isNegative() Description:

    Returns true if this number is less than zero
    
---

!!! signature "public Fraction->isPositive()"
    ##### isPositive
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isPositive() Description:

    Returns true if this number is larger than zero
    
---

!!! signature "public Fraction->isWhole()"
    ##### isWhole
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isWhole() Description:

    Alias for isInt(). Returns true if this number has no non-zero digits in the decimal part.
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."