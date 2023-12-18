# Samsara\Fermat\Core\Types\Traits > CalculationModeTrait

*No description available*


## Methods


### Instanced Methods

!!! signature "public CalculationModeTrait->getMode()"
    ##### getMode
    **return**

    type
    :   ?Samsara\Fermat\Core\Enums\CalcMode

    description
    :   *No description available*

    ###### getMode() Description:

    Returns the enum setting for this object's calculation mode. If this is null, then the default mode in the CalculationModeProvider at the time a calculation is performed will be used.
    
---

!!! signature "public CalculationModeTrait->getResolvedMode()"
    ##### getResolvedMode
    **return**

    type
    :   Samsara\Fermat\Core\Enums\CalcMode

    description
    :   *No description available*

    ###### getResolvedMode() Description:

    Returns the mode that this object would use at the moment, accounting for all values and defaults.
    
---

!!! signature "public CalculationModeTrait->setMode(CalcMode|null $mode)"
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