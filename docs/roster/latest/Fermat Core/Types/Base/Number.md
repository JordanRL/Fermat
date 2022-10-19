# Samsara\Fermat\Core\Types\Base > Number

*No description available*


## Inheritance


### Implements

!!! signature interface "Hashable"
    ##### Hashable
    namespace
    :   Ds

    description
    :   

    Hashable is an interface which allows objects to be used as keys.
    
     It’s an alternative to spl_object_hash(), which determines an object’s hash based on its handle: this means that two objects that are considered equal by an implicit definition would not treated as equal because they are not the same instance.

!!! signature interface "NumberInterface"
    ##### NumberInterface
    namespace
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Numbers

    description
    :   

    *No description available*

!!! signature interface "Stringable"
    ##### Stringable
    namespace
    :   

    description
    :   

    *No description available*



## Variables & Data


### Class Constants

!!! signature constant "Number::INFINITY"
    ##### INFINITY
    value
    :   'INF'

!!! signature constant "Number::NEG_INFINITY"
    ##### NEG_INFINITY
    value
    :   '-INF'



## Methods


### Constructor

!!! signature "public Number->__construct()"
    ##### __construct
    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*
    
---



### Instanced Methods

!!! signature "public Number->setMode(CalcMode $mode)"
    ##### setMode
    **$mode**

    type
    :   CalcMode

    description
    :   *No description available*

    **return**

    type
    :   self

    description
    :   *No description available*

    ###### setMode() Description:

    Allows you to set a mode on a number to select the calculation methods.
    
     MODE_PRECISION: Use what is necessary to provide an answer that is accurate to the scale setting.
 MODE_NATIVE: Use built-in functions to perform the math, and accept whatever rounding or truncation this might cause.
    
---

!!! signature "public Number->getMode()"
    ##### getMode
    **return**

    type
    :   Samsara\Fermat\Core\Enums\CalcMode

    description
    :   *No description available*
    
---

!!! signature "public Number->getValue()"
    ##### getValue
    **return**

    type
    :   string

    description
    :   *No description available*

    ###### getValue() Description:

    Returns the current value as a string.
    
---

!!! signature "public Number->setExtensions(bool $flag)"
    ##### setExtensions
    **$flag**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   self

    description
    :   *No description available*

    ###### setExtensions() Description:

    Allows the object to ignore PHP extensions (such a GMP) and use only the Fermat implementations. NOTE: This does
 not ignore ext-bcmath or ext-decimal, as those are necessary for the string math itself.
    
---

!!! signature "public Number->__toString()"
    ##### __toString
    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public Number->hash()"
    ##### hash
    **return**

    type
    :   string

    description
    :   *No description available*

    ###### hash() Description:

    Implemented to satisfy Hashable implementation
    
---

!!! signature "public Number->equals(mixed $object)"
    ##### equals
    **$object**

    type
    :   mixed

    description
    :   *No description available*

    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### equals() Description:

    Implemented to satisfy Hashable implementation
    
---

!!! signature "public Number->isImaginary()"
    ##### isImaginary
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isImaginary() Description:

    This function returns true if the number is imaginary, and false in the number is real or complex
    
---

!!! signature "public Number->isReal()"
    ##### isReal
    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isReal() Description:

    This function returns true if the number is real, and false if the number is imaginary or complex
    
---

!!! signature "public Number->asReal()"
    ##### asReal
    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public Number->getAsBaseTenRealNumber()"
    ##### getAsBaseTenRealNumber
    **return**

    type
    :   string

    description
    :   *No description available*
    
---

!!! signature "public Number->isComplex()"
    ##### isComplex
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public Number->asComplex()"
    ##### asComplex
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableComplexNumber

    description
    :   *No description available*
    
---

!!! signature "public Number->getBase()"
    ##### getBase
    **return**

    type
    :   Samsara\Fermat\Core\Enums\NumberBase

    description
    :   *No description available*
    
---



### Inherited Methods

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




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."