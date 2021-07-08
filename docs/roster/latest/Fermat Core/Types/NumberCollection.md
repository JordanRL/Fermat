# Samsara\Fermat\Types > NumberCollection

*No description available*


## Inheritance


### Implements

!!! signature interface "NumberCollectionInterface"
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Groups

    description
    :   *No description available*

!!! signature interface "ArrayAccess"
    namespace
    :   

    description
    :   *No description available*

!!! signature interface "IteratorAggregate"
    namespace
    :   

    description
    :   *No description available*

!!! signature interface "Traversable"
    namespace
    :   

    description
    :   *No description available*



## Methods


### Constructor

!!! signature "public NumberCollection->__construct(array $numbers)"
    **$numbers**

    type
    :   array

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

    **NumberCollection->__construct Description**

    NumberCollection constructor.

---



### Instanced Methods

!!! signature "public NumberCollection->collect(array $numbers)"
    **$numbers**

    type
    :   array

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*

---

!!! signature "public NumberCollection->count()"
    **return**

    type
    :   int

    description
    :   *No description available*

---

!!! signature "public NumberCollection->toArray()"
    **return**

    type
    :   array

    description
    :   *No description available*

---

!!! signature "public NumberCollection->selectScale()"
    **return**

    type
    :   int

    description
    :   *No description available*

---

!!! signature "public NumberCollection->push(NumberInterface $number)"
    **$number**

    type
    :   NumberInterface

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*

---

!!! signature "public NumberCollection->pop()"
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface

    description
    :   *No description available*

---

!!! signature "public NumberCollection->unshift(NumberInterface $number)"
    **$number**

    type
    :   NumberInterface

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*

---

!!! signature "public NumberCollection->shift()"
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface

    description
    :   *No description available*

---

!!! signature "public NumberCollection->filterByKeys(array $filters)"
    **$filters**

    type
    :   array

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Types\NumberCollection

    description
    :   *No description available*

---

!!! signature "public NumberCollection->sort()"
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*

---

!!! signature "public NumberCollection->reverse()"
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*

---

!!! signature "public NumberCollection->add($number)"
    **$number**

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*

---

!!! signature "public NumberCollection->subtract($number)"
    **$number**

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*

---

!!! signature "public NumberCollection->multiply($number)"
    **$number**

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*

---

!!! signature "public NumberCollection->divide($number)"
    **$number**

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*

---

!!! signature "public NumberCollection->pow($number)"
    **$number**

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*

    **NumberCollection->pow Description**

    Raises each element in the collection to the exponent $number

---

!!! signature "public NumberCollection->exp($base)"
    **$base**

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*

    **NumberCollection->exp Description**

    Replaces each element in the collection with $base to the power of that value. If no base is given, Euler's number is assumed to be the base (as is assumed in most cases where an exp() function is encountered in math)

---

!!! signature "public NumberCollection->get(int $key)"
    **$key**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface

    description
    :   *No description available*

---

!!! signature "public NumberCollection->getRandom()"
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface

    description
    :   *No description available*

---

!!! signature "public NumberCollection->sum()"
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface

    description
    :   *No description available*

---

!!! signature "public NumberCollection->mean()"
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*

---

!!! signature "public NumberCollection->average()"
    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*

---

!!! signature "public NumberCollection->makeNormalDistribution()"
    **return**

    type
    :   Samsara\Fermat\Provider\Distribution\Normal

    description
    :   *No description available*

---

!!! signature "public NumberCollection->makePoissonDistribution()"
    **return**

    type
    :   Samsara\Fermat\Provider\Distribution\Poisson

    description
    :   *No description available*

---

!!! signature "public NumberCollection->makeExponentialDistribution()"
    **return**

    type
    :   Samsara\Fermat\Provider\Distribution\Exponential

    description
    :   *No description available*

---

!!! signature "public NumberCollection->makePolynomialFunction()"
    **return**

    type
    :   Samsara\Fermat\Values\Algebra\PolynomialFunction

    description
    :   *No description available*

---

!!! signature "public NumberCollection->offsetExists($offset)"
    **$offset**

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

---

!!! signature "public NumberCollection->offsetGet($offset)"
    **$offset**

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

---

!!! signature "public NumberCollection->offsetSet($offset, $value)"
    **$offset**

    description
    :   *No description available*

    **$value**

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

---

!!! signature "public NumberCollection->offsetUnset($offset)"
    **$offset**

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

---

!!! signature "public NumberCollection->getIterator()"
    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."