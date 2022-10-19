# Samsara\Fermat\Core\Types\Base\Interfaces\Numbers > NumberInterface

*No description available*


## Methods


### Instanced Methods

!!! signature "public NumberInterface->abs()"
    ##### abs
    **return**

    type
    :   NumberInterface|DecimalInterface|FractionInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberInterface->absValue()"
    ##### absValue
    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public NumberInterface->add($num)"
    ##### add
    **$num**

    description
    :   
    
    

    **return**

    type
    :   NumberInterface|DecimalInterface|FractionInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberInterface->subtract($num)"
    ##### subtract
    **$num**

    description
    :   
    
    

    **return**

    type
    :   NumberInterface|DecimalInterface|FractionInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberInterface->multiply($num)"
    ##### multiply
    **$num**

    description
    :   
    
    

    **return**

    type
    :   NumberInterface|DecimalInterface|FractionInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberInterface->divide($num, int|null $scale)"
    ##### divide
    **$num**

    description
    :   *No description available*

    **$scale**

    type
    :   int|null

    description
    :   
    
    

    **return**

    type
    :   NumberInterface|DecimalInterface|FractionInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberInterface->pow($num)"
    ##### pow
    **$num**

    description
    :   
    
    

    **return**

    type
    :   NumberInterface|DecimalInterface|FractionInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberInterface->sqrt(int? $scale)"
    ##### sqrt
    **$scale**

    type
    :   int?

    description
    :   
    
    

    **return**

    type
    :   NumberInterface|DecimalInterface|FractionInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberInterface->isEqual(float|int|string|NumberInterface $value)"
    ##### isEqual
    **$value**

    type
    :   float|int|string|NumberInterface

    description
    :   
    
    

    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public NumberInterface->getScale()"
    ##### getScale
    **return**

    type
    :   ?int

    description
    :   *No description available*
    
---

!!! signature "public NumberInterface->isImaginary()"
    ##### isImaginary
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public NumberInterface->isReal()"
    ##### isReal
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public NumberInterface->asReal()"
    ##### asReal
    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public NumberInterface->isComplex()"
    ##### isComplex
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public NumberInterface->asComplex()"
    ##### asComplex
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableComplexNumber

    description
    :   *No description available*
    
---

!!! signature "public NumberInterface->getValue()"
    ##### getValue
    **return**

    type
    :   string

    description
    :   *No description available*

    ###### getValue() Description:

    Returns the current value as a string.
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."