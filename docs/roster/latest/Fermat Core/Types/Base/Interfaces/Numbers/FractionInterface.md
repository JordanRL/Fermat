# Samsara\Fermat\Types\Base\Interfaces\Numbers > FractionInterface

*No description available*


## Inheritance


## Methods


### Instanced Methods

!!! signature "public FractionInterface->simplify()"
    **return**

    type
    :   FractionInterface

    description
    :   *No description available*

---

!!! signature "public FractionInterface->getNumerator()"
    **return**

    type
    :   DecimalInterface

    description
    :   *No description available*

---

!!! signature "public FractionInterface->getDenominator()"
    **return**

    type
    :   DecimalInterface

    description
    :   *No description available*

---

!!! signature "public FractionInterface->getSmallestCommonDenominator(FractionInterface $fraction)"
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
    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---



### Inherited Methods

!!! signature "public SimpleNumberInterface->compare($value)"
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
    **return**

    type
    :   bool

    description
    :   *No description available*

---

!!! signature "public SimpleNumberInterface->isPositive()"
    **return**

    type
    :   bool

    description
    :   *No description available*

---

!!! signature "public SimpleNumberInterface->getAsBaseTenRealNumber()"
    **return**

    type
    :   string

    description
    :   *No description available*

---

!!! signature "public SimpleNumberInterface->getValue()"
    **return**

    type
    :   string

    description
    :   *No description available*

---

!!! signature "public SimpleNumberInterface->isGreaterThan(int|string|NumberInterface $value)"
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
    **return**

    type
    :   NumberInterface|DecimalInterface|FractionInterface

    description
    :   *No description available*

---

!!! signature "public NumberInterface->absValue()"
    **return**

    type
    :   string

    description
    :   *No description available*

---

!!! signature "public NumberInterface->add($num)"
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

!!! signature "public NumberInterface->isEqual(int|string|NumberInterface $value)"
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

!!! signature "public NumberInterface->getScale()"
    **return**

    type
    :   ?int

    description
    :   *No description available*

---

!!! signature "public NumberInterface->isImaginary()"
    **return**

    type
    :   bool

    description
    :   *No description available*

---

!!! signature "public NumberInterface->isReal()"
    **return**

    type
    :   bool

    description
    :   *No description available*

---

!!! signature "public NumberInterface->asReal()"
    **return**

    type
    :   string

    description
    :   *No description available*

---

!!! signature "public NumberInterface->isComplex()"
    **return**

    type
    :   bool

    description
    :   *No description available*

---

!!! signature "public NumberInterface->asComplex()"
    **return**

    type
    :   Samsara\Fermat\Types\ComplexNumber

    description
    :   *No description available*

---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."