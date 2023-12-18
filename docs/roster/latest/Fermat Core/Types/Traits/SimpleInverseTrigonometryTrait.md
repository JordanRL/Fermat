# Samsara\Fermat\Core\Types\Traits > SimpleInverseTrigonometryTrait

*No description available*


## Inheritance


### Has Traits

!!! signature trait "InverseTrigonometryNativeTrait"
    ##### InverseTrigonometryNativeTrait
    namespace
    :   Samsara\Fermat\Core\Types\Base\Traits

    description
    :   

    *No description available*

!!! signature trait "InverseTrigonometryScaleTrait"
    ##### InverseTrigonometryScaleTrait
    namespace
    :   Samsara\Fermat\Core\Types\Base\Traits

    description
    :   

    *No description available*

!!! signature trait "InverseTrigonometrySelectionTrait"
    ##### InverseTrigonometrySelectionTrait
    namespace
    :   Samsara\Fermat\Core\Types\Base\Traits

    description
    :   

    *No description available*



## Methods


### Instanced Methods

!!! signature "public SimpleInverseTrigonometryTrait->arccos(int|null $scale, bool $round)"
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

!!! signature "public SimpleInverseTrigonometryTrait->arccot(int|null $scale, bool $round)"
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

!!! signature "public SimpleInverseTrigonometryTrait->arccsc(int|null $scale, bool $round)"
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

!!! signature "public SimpleInverseTrigonometryTrait->arcsec(int|null $scale, bool $round)"
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

!!! signature "public SimpleInverseTrigonometryTrait->arcsin(int|null $scale, bool $round)"
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

!!! signature "public SimpleInverseTrigonometryTrait->arctan(int|null $scale, bool $round)"
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

!!! signature "public SimpleInverseTrigonometryTrait->getScale()"
    ##### getScale
    **return**

    type
    :   ?int

    description
    :   *No description available*
    
---

!!! signature "public SimpleInverseTrigonometryTrait->roundToScale(int $scale, RoundingMode|null $mode)"
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
    :   Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Core\Values\MutableDecimal|static

    description
    :   *No description available*
    
---

!!! signature "public SimpleInverseTrigonometryTrait->truncateToScale(int $scale)"
    ##### truncateToScale
    **$scale**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Core\Values\MutableDecimal|static

    description
    :   *No description available*
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."