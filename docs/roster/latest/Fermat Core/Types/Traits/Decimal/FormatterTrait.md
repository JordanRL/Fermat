# Samsara\Fermat\Core\Types\Traits\Decimal > FormatterTrait

*No description available*


## Methods


### Static Methods

!!! signature "public FormatterTrait::createFromFormat(NumberFormat $format, NumberGrouping $grouping, string $value, int|null $scale, NumberBase $base, bool $baseTenInput)"
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



### Instanced Methods

!!! signature "public FormatterTrait->getCurrencyValue(Currency $currency)"
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

!!! signature "public FormatterTrait->getFormat()"
    ##### getFormat
    **return**

    type
    :   Samsara\Fermat\Core\Enums\NumberFormat

    description
    :   *No description available*

    ###### getFormat() Description:

    Gets the current format setting of this number.
    
---

!!! signature "public FormatterTrait->setFormat(NumberFormat $format)"
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

!!! signature "public FormatterTrait->getFormattedValue(NumberBase|null $base)"
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

!!! signature "public FormatterTrait->getGrouping()"
    ##### getGrouping
    **return**

    type
    :   Samsara\Fermat\Core\Enums\NumberGrouping

    description
    :   *No description available*

    ###### getGrouping() Description:

    Gets the current number grouping setting of this number.
    
---

!!! signature "public FormatterTrait->setGrouping(NumberGrouping $grouping)"
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

!!! signature "public FormatterTrait->getScientificValue(int|null $scale)"
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

!!! signature "public FormatterTrait->getDecimalPart()"
    ##### getDecimalPart
    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public FormatterTrait->getValue(NumberBase $base)"
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

!!! signature "public FormatterTrait->getWholePart()"
    ##### getWholePart
    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public FormatterTrait->isImaginary()"
    ##### isImaginary
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public FormatterTrait->isNegative()"
    ##### isNegative
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public FormatterTrait->numberOfDecimalDigits()"
    ##### numberOfDecimalDigits
    **return**

    type
    :   int

    description
    :   *No description available*
    
---

!!! signature "public FormatterTrait->numberOfIntDigits()"
    ##### numberOfIntDigits
    **return**

    type
    :   int

    description
    :   *No description available*
    
---

!!! signature "public FormatterTrait->numberOfLeadingZeros()"
    ##### numberOfLeadingZeros
    **return**

    type
    :   int

    description
    :   *No description available*
    
---

!!! signature "public FormatterTrait->numberOfSigDecimalDigits()"
    ##### numberOfSigDecimalDigits
    **return**

    type
    :   int

    description
    :   *No description available*
    
---

!!! signature "public FormatterTrait->numberOfTotalDigits()"
    ##### numberOfTotalDigits
    **return**

    type
    :   int

    description
    :   *No description available*
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."