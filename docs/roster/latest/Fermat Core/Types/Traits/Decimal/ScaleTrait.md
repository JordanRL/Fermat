# Samsara\Fermat\Core\Types\Traits\Decimal > ScaleTrait

*No description available*


## Methods


### Instanced Methods

!!! signature "public ScaleTrait->getDecimalPart()"
    ##### getDecimalPart
    **return**

    type
    :   string

    description
    :   *No description available*

    ###### getDecimalPart() Description:

    Returns only the decimal part of the number as a string.
    
---

!!! signature "public ScaleTrait->getScale()"
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

!!! signature "public ScaleTrait->getWholePart()"
    ##### getWholePart
    **return**

    type
    :   string

    description
    :   *No description available*

    ###### getWholePart() Description:

    Returns only the integer part of the number as a string.
    
---

!!! signature "public ScaleTrait->isFloat()"
    ##### isFloat
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isFloat() Description:

    Returns true if any non-zero digits exist in the decimal part.
    
---

!!! signature "public ScaleTrait->asFloat()"
    ##### asFloat
    **return**

    type
    :   float

    description
    :   *No description available*

    ###### asFloat() Description:

    Returns the current value as a float if it is within the max and min float values on the current system. Uses the float) explicit cast to convert the string to a float type.
    
---

!!! signature "public ScaleTrait->asInt()"
    ##### asInt
    **return**

    type
    :   int

    description
    :   *No description available*

    ###### asInt() Description:

    Returns the current value as an integer if it is within the max and min int values on the current system. Uses the intval() function to convert the string to an integer type.
    
---

!!! signature "public ScaleTrait->ceil()"
    ##### ceil
    **return**

    type
    :   static

    description
    :   *No description available*

    ###### ceil() Description:

    Round to the next integer closest to positive infinity.
    
---

!!! signature "public ScaleTrait->floor()"
    ##### floor
    **return**

    type
    :   static

    description
    :   *No description available*

    ###### floor() Description:

    Round to the next integer closest to negative infinity.
    
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

!!! signature "public ScaleTrait->numberOfLeadingZeros()"
    ##### numberOfLeadingZeros
    **return**

    type
    :   int

    description
    :   *No description available*

    ###### numberOfLeadingZeros() Description:

    The number of digits between the radix and the first non-zero digit in the decimal part.
    
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

!!! signature "public ScaleTrait->round(int $decimals, RoundingMode|null $mode)"
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

!!! signature "public ScaleTrait->roundToScale(int $scale, RoundingMode|null $mode)"
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

!!! signature "public ScaleTrait->truncate(int $decimals)"
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

!!! signature "public ScaleTrait->truncateToScale(int $scale)"
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




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."