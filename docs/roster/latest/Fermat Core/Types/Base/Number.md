# Samsara\Fermat\Types\Base > Number

*No description available*


## Inheritance


### Implements

!!! signature interface "Hashable"
    namespace
    :   Ds

    description
    :   Hashable is an interface which allows objects to be used as keys.
    
     It’s an alternative to spl_object_hash(), which determines an object’s hash based on its handle: this means that two objects that are considered equal by an implicit definition would not treated as equal because they are not the same instance.

!!! signature interface "NumberInterface"
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers

    description
    :   *No description available*

!!! signature interface "Stringable"
    namespace
    :   

    description
    :   *No description available*



## Variables & Data


### Class Constants

!!! signature constant "Number::INFINITY"
    value
    :   'INF'

!!! signature constant "Number::NEG_INFINITY"
    value
    :   '-INF'



## Methods


### Constructor

!!! signature "public Number->__construct()"
    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

---



### Instanced Methods

!!! signature "public Number->setMode(int $mode)"
    **$mode**

    type
    :   int

    description
    :   *No description available*

    **return**

    type
    :   self

    description
    :   *No description available*

    **Number->setMode Description**

    Allows you to set a mode on a number to select the calculation methods.
    
     MODE_PRECISION: Use what is necessary to provide an answer that is accurate to the scale setting. MODE_NATIVE: Use built-in functions to perform the math, and accept whatever rounding or truncation this might cause.

---

!!! signature "public Number->getValue()"
    **return**

    type
    :   string

    description
    :   *No description available*

    **Number->getValue Description**

    Returns the current value as a string.

---

!!! signature "public Number->setExtensions(bool $flag)"
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

    **Number->setExtensions Description**

    Allows the object to ignore PHP extensions (such a GMP) and use only the Fermat implementations. NOTE: This does not ignore ext-bcmath or ext-decimal, as those are necessary for the string math itself.

---

!!! signature "public Number->__toString()"
    **return**

    type
    :   string

    description
    :   *No description available*

---

!!! signature "public Number->hash()"
    **return**

    type
    :   string

    description
    :   *No description available*

    **Number->hash Description**

    Implemented to satisfy Hashable implementation

---

!!! signature "public Number->equals(mixed $object)"
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

    **Number->equals Description**

    Implemented to satisfy Hashable implementation

---

!!! signature "public Number->isImaginary()"
    **return**

    type
    :   bool

    description
    :   *No description available*

    **Number->isImaginary Description**

    This function returns true if the number is imaginary, and false in the number is real or complex

---

!!! signature "public Number->isReal()"
    **return**

    type
    :   bool

    description
    :   *No description available*

    **Number->isReal Description**

    This function returns true if the number is real, and false if the number is imaginary or complex

---

!!! signature "public Number->asReal()"
    **return**

    type
    :   string

    description
    :   *No description available*

---

!!! signature "public Number->getAsBaseTenRealNumber()"
    **return**

    type
    :   string

    description
    :   *No description available*

---

!!! signature "public Number->isComplex()"
    **return**

    type
    :   bool

    description
    :   *No description available*

---

!!! signature "public Number->asComplex()"
    **return**

    type
    :   Samsara\Fermat\Types\ComplexNumber

    description
    :   *No description available*

---



### Inherited Methods

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




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."