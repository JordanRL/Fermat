# Samsara\Fermat\Core\Types\Traits > ComparisonTrait

*No description available*


## Methods


### Instanced Methods

!!! signature "public ComparisonTrait->isEqual(Number|int|string|float $value)"
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

!!! signature "public ComparisonTrait->isGreaterThan(Number|int|string|float $value)"
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

!!! signature "public ComparisonTrait->isGreaterThanOrEqualTo(Number|int|string|float $value)"
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

!!! signature "public ComparisonTrait->isInt()"
    ##### isInt
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isInt() Description:

    Returns true if this number has no non-zero digits in the decimal part.
    
---

!!! signature "public ComparisonTrait->isLessThan(Number|int|string|float $value)"
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

!!! signature "public ComparisonTrait->isLessThanOrEqualTo(Number|int|string|float $value)"
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

!!! signature "public ComparisonTrait->isNatural()"
    ##### isNatural
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isNatural() Description:

    Alias for isInt(). Returns true if this number has no non-zero digits in the decimal part.
    
---

!!! signature "public ComparisonTrait->isNegative()"
    ##### isNegative
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isNegative() Description:

    Returns true if this number is less than zero
    
---

!!! signature "public ComparisonTrait->isPositive()"
    ##### isPositive
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isPositive() Description:

    Returns true if this number is larger than zero
    
---

!!! signature "public ComparisonTrait->isWhole()"
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