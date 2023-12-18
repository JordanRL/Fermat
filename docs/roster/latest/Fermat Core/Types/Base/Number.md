# Samsara\Fermat\Core\Types\Base > Number

*No description available*


## Inheritance


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



### Has Traits

!!! signature trait "CalculationModeTrait"
    ##### CalculationModeTrait
    namespace
    :   Samsara\Fermat\Core\Types\Traits

    description
    :   

    *No description available*



## Variables & Data


### Class Constants

!!! signature constant "Number::INFINITY"
    ##### INFINITY
    value
    :   'INF'

!!! signature constant "Number::NEG_INFINITY"
    ##### NEG_INFINITY
    value
    :   '-INF'



## Methods


### Constructor

!!! signature "public Number->__construct()"
    ##### __construct
    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*
    
---



### Instanced Methods

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

!!! signature "public Number->absValue()"
    ##### absValue
    **return**

    type
    :   string

    description
    :   *No description available*

    ###### absValue() Description:

    Returns the string of the absolute value of the current object.
    
---

!!! signature "public Number->asComplex()"
    ##### asComplex
    **return**

    type
    :   Samsara\Fermat\Complex\Values\ImmutableComplexNumber

    description
    :   *No description available*
    
---

!!! signature "public Number->asImaginary()"
    ##### asImaginary
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Core\Values\ImmutableFraction

    description
    :   *No description available*

    ###### asImaginary() Description:

    Returns a new instance of this object with a base ten imaginary number.
    
---

!!! signature "public Number->asReal()"
    ##### asReal
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Core\Values\ImmutableFraction

    description
    :   *No description available*

    ###### asReal() Description:

    Returns a new instance of this object with a base ten real number.
    
---

!!! signature "public Number->getAsBaseTenRealNumber()"
    ##### getAsBaseTenRealNumber
    **return**

    type
    :   string

    description
    :   *No description available*

    ###### getAsBaseTenRealNumber() Description:

    Returns the current value as a string in base 10, converted to a real number. If the number is imaginary, the i is simply not printed. If the number is complex, then the absolute value is returned.
    
---

!!! signature "public Number->getValue()"
    ##### getValue
    **return**

    type
    :   string

    description
    :   *No description available*

    ###### getValue() Description:

    Returns the current value as a string.
    
---

!!! signature "public Number->isComplex()"
    ##### isComplex
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isComplex() Description:

    Returns true if the number is complex, false if the number is real or imaginary.
    
---

!!! signature "public Number->isEqual(Number|int|string|float $value)"
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

!!! signature "public Number->isGreaterThan(Number|int|string|float $value)"
    ##### isGreaterThan
    **$value**

    type
    :   Number|int|string|float

    description
    :   The value to compare against
    
    

    **return**

    type
    :   ?bool

    description
    :   *No description available*

    ###### isGreaterThan() Description:

    Compares this number to another number and returns true if this number is closer to positive infinity.
    
---

!!! signature "public Number->isGreaterThanOrEqualTo(Number|int|string|float $value)"
    ##### isGreaterThanOrEqualTo
    **$value**

    type
    :   Number|int|string|float

    description
    :   The value to compare against
    
    

    **return**

    type
    :   ?bool

    description
    :   *No description available*

    ###### isGreaterThanOrEqualTo() Description:

    Compares this number to another number and returns true if this number is closer to positive infinity or equal.
    
---

!!! signature "public Number->isLessThan(Number|int|string|float $value)"
    ##### isLessThan
    **$value**

    type
    :   Number|int|string|float

    description
    :   The value to compare against
    
    

    **return**

    type
    :   ?bool

    description
    :   *No description available*

    ###### isLessThan() Description:

    Compares this number to another number and returns true if this number is closer to negative infinity.
    
---

!!! signature "public Number->isLessThanOrEqualTo(Number|int|string|float $value)"
    ##### isLessThanOrEqualTo
    **$value**

    type
    :   Number|int|string|float

    description
    :   The value to compare against
    
    

    **return**

    type
    :   ?bool

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




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."