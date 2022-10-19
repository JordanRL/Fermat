# Samsara\Fermat\Core\Types\Base\Interfaces\Numbers > FractionInterface

*No description available*


## Inheritance


## Methods


### Instanced Methods

!!! signature "public FractionInterface->simplify()"
    ##### simplify
    **return**

    type
    :   FractionInterface

    description
    :   *No description available*
    
---

!!! signature "public FractionInterface->getNumerator()"
    ##### getNumerator
    **return**

    type
    :   DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public FractionInterface->getDenominator()"
    ##### getDenominator
    **return**

    type
    :   DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public FractionInterface->getSmallestCommonDenominator(FractionInterface $fraction)"
    ##### getSmallestCommonDenominator
    **$fraction**

    type
    :   FractionInterface

    description
    :   
    
    

    **return**

    type
    :   DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public FractionInterface->asDecimal()"
    ##### asDecimal
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---



### Inherited Methods

!!! signature "public SimpleNumberInterface->compare($value)"
    ##### compare
    **$value**

    description
    :   
    
    

    **return**

    type
    :   int

    description
    :   *No description available*
    
---

!!! signature "public SimpleNumberInterface->isNegative()"
    ##### isNegative
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public SimpleNumberInterface->isPositive()"
    ##### isPositive
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public SimpleNumberInterface->getAsBaseTenRealNumber()"
    ##### getAsBaseTenRealNumber
    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public SimpleNumberInterface->getValue()"
    ##### getValue
    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public SimpleNumberInterface->isGreaterThan(int|string|NumberInterface $value)"
    ##### isGreaterThan
    **$value**

    type
    :   int|string|NumberInterface

    description
    :   
    
    

    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public SimpleNumberInterface->isLessThan(int|string|NumberInterface $value)"
    ##### isLessThan
    **$value**

    type
    :   int|string|NumberInterface

    description
    :   
    
    

    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public SimpleNumberInterface->isGreaterThanOrEqualTo(int|string|NumberInterface $value)"
    ##### isGreaterThanOrEqualTo
    **$value**

    type
    :   int|string|NumberInterface

    description
    :   
    
    

    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public SimpleNumberInterface->isLessThanOrEqualTo(int|string|NumberInterface $value)"
    ##### isLessThanOrEqualTo
    **$value**

    type
    :   int|string|NumberInterface

    description
    :   
    
    

    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

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




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."