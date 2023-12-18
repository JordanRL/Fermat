# Samsara\Fermat\Core\Types\Traits > SimpleLogTrait

*No description available*


## Inheritance


### Has Traits

!!! signature trait "LogNativeTrait"
    ##### LogNativeTrait
    namespace
    :   Samsara\Fermat\Core\Types\Base\Traits

    description
    :   

    *No description available*

!!! signature trait "LogScaleTrait"
    ##### LogScaleTrait
    namespace
    :   Samsara\Fermat\Core\Types\Base\Traits

    description
    :   

    *No description available*

!!! signature trait "LogSelectionTrait"
    ##### LogSelectionTrait
    namespace
    :   Samsara\Fermat\Core\Types\Base\Traits

    description
    :   

    *No description available*



## Methods


### Instanced Methods

!!! signature "public SimpleLogTrait->exp(int|null $scale, bool $round)"
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

!!! signature "public SimpleLogTrait->ln(int|null $scale, bool $round)"
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

!!! signature "public SimpleLogTrait->log10(int|null $scale, bool $round)"
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




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."