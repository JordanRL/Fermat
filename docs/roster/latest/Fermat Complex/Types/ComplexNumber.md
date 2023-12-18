# Samsara\Fermat\Complex\Types > ComplexNumber

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Core\Types\Base\Number


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

!!! signature interface "ComplexNumberInterface"
    ##### ComplexNumberInterface
    namespace
    :   Samsara\Fermat\Complex\Types\Base\Interfaces\Numbers

    description
    :   

    *No description available*



### Has Traits

!!! signature trait "ArithmeticComplexTrait"
    ##### ArithmeticComplexTrait
    namespace
    :   Samsara\Fermat\Complex\Types\Traits

    description
    :   

    *No description available*

!!! signature trait "CalculationModeTrait"
    ##### CalculationModeTrait
    namespace
    :   Samsara\Fermat\Core\Types\Traits

    description
    :   

    *No description available*

!!! signature trait "ComplexScaleTrait"
    ##### ComplexScaleTrait
    namespace
    :   Samsara\Fermat\Complex\Types\Traits

    description
    :   

    *No description available*



## Variables & Data


### Class Constants

!!! signature constant "ComplexNumber::INFINITY"
    ##### INFINITY
    value
    :   'INF'

!!! signature constant "ComplexNumber::NEG_INFINITY"
    ##### NEG_INFINITY
    value
    :   '-INF'



## Methods


### Constructor

!!! signature "public ComplexNumber->__construct(ImmutableDecimal|ImmutableFraction $realPart, ImmutableDecimal|ImmutableFraction $imaginaryPart, int|null $scale, NumberBase $base)"
    ##### __construct
    **$realPart**

    type
    :   ImmutableDecimal|ImmutableFraction

    description
    :   *No description available*

    **$imaginaryPart**

    type
    :   ImmutableDecimal|ImmutableFraction

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
    :   
    
    

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*
    
---



### Static Methods

!!! signature "public ComplexNumber::makeFromArray(array $number, int|null $scale, NumberBase $base)"
    ##### makeFromArray
    **$number**

    type
    :   array

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
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*

---

!!! signature "public ComplexNumber::makeFromString(string $expression, int|null $scale, NumberBase $base)"
    ##### makeFromString
    **$expression**

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
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*

---



### Instanced Methods

!!! signature "public ComplexNumber->getAsBaseTenRealNumber()"
    ##### getAsBaseTenRealNumber
    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->getDistanceFromOrigin()"
    ##### getDistanceFromOrigin
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->getImaginaryPart()"
    ##### getImaginaryPart
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Core\Values\ImmutableFraction

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->getPolarAngle()"
    ##### getPolarAngle
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->getRealPart()"
    ##### getRealPart
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Core\Values\ImmutableFraction

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->getValue(NumberBase $base)"
    ##### getValue
    **$base**

    type
    :   NumberBase

    description
    :   
    
    

    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->setMode(CalcMode|null $mode)"
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

!!! signature "public ComplexNumber->isComplex()"
    ##### isComplex
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->isEqual(string|int|float|Number $value)"
    ##### isEqual
    **$value**

    type
    :   string|int|float|Number

    description
    :   
    
    

    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->isGreaterThan($value)"
    ##### isGreaterThan
    **$value**

    description
    :   
    
    

    **return**

    type
    :   ?bool

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->isGreaterThanOrEqualTo($value)"
    ##### isGreaterThanOrEqualTo
    **$value**

    description
    :   
    
    

    **return**

    type
    :   ?bool

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->isImaginary()"
    ##### isImaginary
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->isLessThan($value)"
    ##### isLessThan
    **$value**

    description
    :   
    
    

    **return**

    type
    :   ?bool

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->isLessThanOrEqualTo($value)"
    ##### isLessThanOrEqualTo
    **$value**

    description
    :   
    
    

    **return**

    type
    :   ?bool

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->isReal()"
    ##### isReal
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->asComplex()"
    ##### asComplex
    **return**

    type
    :   Samsara\Fermat\Complex\Values\ImmutableComplexNumber

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->asImaginary()"
    ##### asImaginary
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->asPolar()"
    ##### asPolar
    **return**

    type
    :   Samsara\Fermat\Coordinates\Values\PolarCoordinate

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->asReal()"
    ##### asReal
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->abs()"
    ##### abs
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->absValue()"
    ##### absValue
    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->getMode()"
    ##### getMode
    **return**

    type
    :   ?Samsara\Fermat\Core\Enums\CalcMode

    description
    :   *No description available*

    ###### getMode() Description:

    Returns the enum setting for this object's calculation mode. If this is null, then the default mode in the CalculationModeProvider at the time a calculation is performed will be used.
    
---

!!! signature "public ComplexNumber->getResolvedMode()"
    ##### getResolvedMode
    **return**

    type
    :   Samsara\Fermat\Core\Enums\CalcMode

    description
    :   *No description available*

    ###### getResolvedMode() Description:

    Returns the mode that this object would use at the moment, accounting for all values and defaults.
    
---

!!! signature "public ComplexNumber->add(string|int|float|Decimal|Fraction|ComplexNumber $num)"
    ##### add
    **$num**

    type
    :   string|int|float|Decimal|Fraction|ComplexNumber

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\MutableDecimal|Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Complex\Values\MutableComplexNumber|Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Core\Values\MutableFraction|Samsara\Fermat\Core\Values\ImmutableFraction|static

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->divide(string|int|float|Decimal|Fraction|ComplexNumber $num, int|null $scale)"
    ##### divide
    **$num**

    type
    :   string|int|float|Decimal|Fraction|ComplexNumber

    description
    :   *No description available*

    **$scale**

    type
    :   int|null

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\MutableDecimal|Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Complex\Values\MutableComplexNumber|Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Core\Values\MutableFraction|Samsara\Fermat\Core\Values\ImmutableFraction|static

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->multiply(string|int|float|Decimal|Fraction|ComplexNumber $num)"
    ##### multiply
    **$num**

    type
    :   string|int|float|Decimal|Fraction|ComplexNumber

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\MutableDecimal|Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Complex\Values\MutableComplexNumber|Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Core\Values\MutableFraction|Samsara\Fermat\Core\Values\ImmutableFraction|static

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->nthRoots(int|ImmutableDecimal $root, int|null $scale)"
    ##### nthRoots
    **$root**

    type
    :   int|ImmutableDecimal

    description
    :   *No description available*

    **$scale**

    type
    :   int|null

    description
    :   
    
    

    **return**

    type
    :   array

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->pow(string|int|float|Decimal|Fraction|ComplexNumber $num, ?int $scale)"
    ##### pow
    **$num**

    type
    :   string|int|float|Decimal|Fraction|ComplexNumber

    description
    :   
    
    

    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Core\Values\MutableDecimal|Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Complex\Values\MutableComplexNumber|Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Core\Values\MutableFraction|Samsara\Fermat\Core\Values\ImmutableFraction|static

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->sqrt(int|null $scale)"
    ##### sqrt
    **$scale**

    type
    :   int|null

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Complex\Values\MutableComplexNumber|Samsara\Fermat\Core\Values\ImmutableDecimal|static

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->subtract(string|int|float|Decimal|Fraction|ComplexNumber $num)"
    ##### subtract
    **$num**

    type
    :   string|int|float|Decimal|Fraction|ComplexNumber

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\MutableDecimal|Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Complex\Values\MutableComplexNumber|Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Core\Values\MutableFraction|Samsara\Fermat\Core\Values\ImmutableFraction|static

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->getScale()"
    ##### getScale
    **return**

    type
    :   int

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->ceil()"
    ##### ceil
    **return**

    type
    :   Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Complex\Values\MutableComplexNumber|static

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->floor()"
    ##### floor
    **return**

    type
    :   Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Complex\Values\MutableComplexNumber|static

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->round(int $decimals, RoundingMode|null $mode)"
    ##### round
    **$decimals**

    type
    :   int

    description
    :   *No description available*

    **$mode**

    type
    :   RoundingMode|null

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Complex\Values\MutableComplexNumber|static

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->roundToScale(int $scale, RoundingMode|null $mode)"
    ##### roundToScale
    **$scale**

    type
    :   int

    description
    :   *No description available*

    **$mode**

    type
    :   RoundingMode|null

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Complex\Values\MutableComplexNumber|static

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->truncate(int $decimals)"
    ##### truncate
    **$decimals**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Complex\Values\MutableComplexNumber|static

    description
    :   *No description available*
    
---

!!! signature "public ComplexNumber->truncateToScale(int $scale)"
    ##### truncateToScale
    **$scale**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Complex\Values\MutableComplexNumber|static

    description
    :   *No description available*
    
---



### Inherited Methods

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




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."