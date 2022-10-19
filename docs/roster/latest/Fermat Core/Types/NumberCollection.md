# Samsara\Fermat\Core\Types > NumberCollection

*No description available*


## Inheritance


### Implements

!!! signature interface "NumberCollectionInterface"
    ##### NumberCollectionInterface
    namespace
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Groups

    description
    :   

    *No description available*

!!! signature interface "ArrayAccess"
    ##### ArrayAccess
    namespace
    :   

    description
    :   

    *No description available*

!!! signature interface "IteratorAggregate"
    ##### IteratorAggregate
    namespace
    :   

    description
    :   

    *No description available*

!!! signature interface "Traversable"
    ##### Traversable
    namespace
    :   

    description
    :   

    *No description available*



## Methods


### Constructor

!!! signature "public NumberCollection->__construct(array $numbers)"
    ##### __construct
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

    ###### __construct() Description:

    NumberCollection constructor.
    
---



### Instanced Methods

!!! signature "public NumberCollection->collect(array $numbers)"
    ##### collect
    **$numbers**

    type
    :   array

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->count()"
    ##### count
    **return**

    type
    :   int

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->toArray()"
    ##### toArray
    **return**

    type
    :   array

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->selectScale()"
    ##### selectScale
    **return**

    type
    :   int

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->push(NumberInterface $number)"
    ##### push
    **$number**

    type
    :   NumberInterface

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->pop()"
    ##### pop
    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\NumberInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->unshift(NumberInterface $number)"
    ##### unshift
    **$number**

    type
    :   NumberInterface

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->shift()"
    ##### shift
    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\NumberInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->filterByKeys(array $filters)"
    ##### filterByKeys
    **$filters**

    type
    :   array

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->sort()"
    ##### sort
    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->reverse()"
    ##### reverse
    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->add($number)"
    ##### add
    **$number**

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->subtract($number)"
    ##### subtract
    **$number**

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->multiply($number)"
    ##### multiply
    **$number**

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->divide($number)"
    ##### divide
    **$number**

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->pow($number)"
    ##### pow
    **$number**

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*

    ###### pow() Description:

    Raises each element in the collection to the exponent $number
    
---

!!! signature "public NumberCollection->exp($base)"
    ##### exp
    **$base**

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*

    ###### exp() Description:

    Replaces each element in the collection with $base to the power of that value. If no base is given, Euler's number is assumed to be the base (as is assumed in most cases where an exp() function is encountered in math)
    
---

!!! signature "public NumberCollection->get(int $key)"
    ##### get
    **$key**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\NumberInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->getRandom()"
    ##### getRandom
    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\NumberInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->sum()"
    ##### sum
    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\NumberInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->mean()"
    ##### mean
    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->average()"
    ##### average
    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->makeNormalDistribution()"
    ##### makeNormalDistribution
    **return**

    type
    :   Samsara\Fermat\Core\Provider\Distribution\Normal

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->makePoissonDistribution()"
    ##### makePoissonDistribution
    **return**

    type
    :   Samsara\Fermat\Core\Provider\Distribution\Poisson

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->makeExponentialDistribution()"
    ##### makeExponentialDistribution
    **return**

    type
    :   Samsara\Fermat\Core\Provider\Distribution\Exponential

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->makePolynomialFunction()"
    ##### makePolynomialFunction
    **return**

    type
    :   Samsara\Fermat\Core\Values\Algebra\PolynomialFunction

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->offsetExists($offset)"
    ##### offsetExists
    **$offset**

    description
    :   *No description available*

    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->offsetGet($offset)"
    ##### offsetGet
    **$offset**

    description
    :   *No description available*

    **return**

    type
    :   mixed

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->offsetSet($offset, $value)"
    ##### offsetSet
    **$offset**

    description
    :   *No description available*

    **$value**

    description
    :   *No description available*

    **return**

    type
    :   void

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->offsetUnset($offset)"
    ##### offsetUnset
    **$offset**

    description
    :   *No description available*

    **return**

    type
    :   void

    description
    :   *No description available*
    
---

!!! signature "public NumberCollection->getIterator()"
    ##### getIterator
    **return**

    type
    :   Traversable

    description
    :   *No description available*
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."