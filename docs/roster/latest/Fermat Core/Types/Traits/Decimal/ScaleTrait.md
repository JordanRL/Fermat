# Samsara\Fermat\Types\Traits\Decimal > ScaleTrait

*No description available*


## Methods


### Instanced Methods

!!! signature "public ScaleTrait->getScale()"
    **return**

    type
    :   ?int

    description
    :   *No description available*

---

!!! signature "public ScaleTrait->round(int $decimals, ?int $mode)"
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

!!! signature "public ScaleTrait->truncate(int $decimals)"
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

!!! signature "public ScaleTrait->roundToScale(int $scale, ?int $mode)"
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

!!! signature "public ScaleTrait->truncateToScale($scale)"
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
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*

---

!!! signature "public ScaleTrait->floor()"
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*

---

!!! signature "public ScaleTrait->numberOfLeadingZeros()"
    **return**

    type
    :   int

    description
    :   *No description available*

    **ScaleTrait->numberOfLeadingZeros Description**

    The number of digits between the radix and the for non-zero digit in the decimal part.

---

!!! signature "public ScaleTrait->numberOfTotalDigits()"
    **return**

    type
    :   int

    description
    :   *No description available*

    **ScaleTrait->numberOfTotalDigits Description**

    The number of digits (excludes the radix).

---

!!! signature "public ScaleTrait->numberOfIntDigits()"
    **return**

    type
    :   int

    description
    :   *No description available*

    **ScaleTrait->numberOfIntDigits Description**

    The number of digits in the integer part.

---

!!! signature "public ScaleTrait->numberOfDecimalDigits()"
    **return**

    type
    :   int

    description
    :   *No description available*

    **ScaleTrait->numberOfDecimalDigits Description**

    The number of digits in the decimal part.

---

!!! signature "public ScaleTrait->numberOfSigDecimalDigits()"
    **return**

    type
    :   int

    description
    :   *No description available*

    **ScaleTrait->numberOfSigDecimalDigits Description**

    The number of digits in the decimal part, excluding leading zeros.

---

!!! signature "public ScaleTrait->asInt()"
    **return**

    type
    :   int

    description
    :   *No description available*

    **ScaleTrait->asInt Description**

    Returns the current value as an integer if it is within the max a min int values on the current system. Uses the intval() function to convert the string to an integer type.

---

!!! signature "public ScaleTrait->isFloat()"
    **return**

    type
    :   bool

    description
    :   *No description available*

---

!!! signature "public ScaleTrait->asFloat()"
    **return**

    type
    :   float

    description
    :   *No description available*

---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."