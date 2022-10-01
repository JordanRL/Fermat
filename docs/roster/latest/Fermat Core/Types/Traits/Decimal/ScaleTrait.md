# Samsara\Fermat\Types\Traits\Decimal > ScaleTrait

*No description available*


## Methods


### Instanced Methods

!!! signature "public ScaleTrait->getScale()"
    ##### getScale
    **return**

    type
    :   int

    description
    :   *No description available*
    
---

!!! signature "public ScaleTrait->round(int $decimals, RoundingMode|null $mode)"
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
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public ScaleTrait->truncate(int $decimals)"
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

!!! signature "public ScaleTrait->roundToScale(int $scale, RoundingMode|null $mode)"
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
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public ScaleTrait->truncateToScale($scale)"
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

!!! signature "public ScaleTrait->ceil()"
    ##### ceil
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public ScaleTrait->floor()"
    ##### floor
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public ScaleTrait->numberOfLeadingZeros()"
    ##### numberOfLeadingZeros
    **return**

    type
    :   int

    description
    :   *No description available*

    ###### numberOfLeadingZeros() Description:

    The number of digits between the radix and the for non-zero digit in the decimal part.
    
---

!!! signature "public ScaleTrait->numberOfTotalDigits()"
    ##### numberOfTotalDigits
    **return**

    type
    :   int

    description
    :   *No description available*

    ###### numberOfTotalDigits() Description:

    The number of digits (excludes the radix).
    
---

!!! signature "public ScaleTrait->numberOfIntDigits()"
    ##### numberOfIntDigits
    **return**

    type
    :   int

    description
    :   *No description available*

    ###### numberOfIntDigits() Description:

    The number of digits in the integer part.
    
---

!!! signature "public ScaleTrait->numberOfDecimalDigits()"
    ##### numberOfDecimalDigits
    **return**

    type
    :   int

    description
    :   *No description available*

    ###### numberOfDecimalDigits() Description:

    The number of digits in the decimal part.
    
---

!!! signature "public ScaleTrait->numberOfSigDecimalDigits()"
    ##### numberOfSigDecimalDigits
    **return**

    type
    :   int

    description
    :   *No description available*

    ###### numberOfSigDecimalDigits() Description:

    The number of digits in the decimal part, excluding leading zeros.
    
---

!!! signature "public ScaleTrait->asInt()"
    ##### asInt
    **return**

    type
    :   int

    description
    :   *No description available*

    ###### asInt() Description:

    Returns the current value as an integer if it is within the max a min int values on the current system. Uses the intval() function to convert the string to an integer type.
    
---

!!! signature "public ScaleTrait->isFloat()"
    ##### isFloat
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public ScaleTrait->asFloat()"
    ##### asFloat
    **return**

    type
    :   float

    description
    :   *No description available*
    
---

!!! signature "public ScaleTrait->getDecimalPart()"
    ##### getDecimalPart
    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public ScaleTrait->getWholePart()"
    ##### getWholePart
    **return**

    type
    :   string

    description
    :   *No description available*
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."