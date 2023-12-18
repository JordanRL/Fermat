# Samsara\Fermat\LinearAlgebra\Types > Vector

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Core\Types\Tuple


### Implements

!!! signature interface "VectorInterface"
    ##### VectorInterface
    namespace
    :   Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups

    description
    :   

    *No description available*



## Methods


### Constructor

!!! signature "public Tuple->__construct($data)"
    ##### __construct
    **$data**

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*
    
---



### Inherited Methods

!!! signature "public Tuple->get(int $index)"
    ##### get
    **$index**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Tuple->set(int $index, ImmutableDecimal $value)"
    ##### set
    **$index**

    type
    :   int

    description
    :   *No description available*

    **$value**

    type
    :   ImmutableDecimal

    description
    :   
    
    

    **return**

    type
    :   self

    description
    :   *No description available*
    
---

!!! signature "public Tuple->all()"
    ##### all
    **return**

    type
    :   array

    description
    :   *No description available*
    
---

!!! signature "public Tuple->hasIndex(int $index)"
    ##### hasIndex
    **$index**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public Tuple->size()"
    ##### size
    **return**

    type
    :   int

    description
    :   *No description available*
    
---

!!! signature "public VectorInterface->add(Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\VectorInterface $vector)"
    ##### add
    **$vector**

    type
    :   Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\VectorInterface

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\VectorInterface

    description
    :   *No description available*
    
---

!!! signature "public VectorInterface->asMatrix()"
    ##### asMatrix
    **return**

    type
    :   Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\MatrixInterface

    description
    :   *No description available*
    
---

!!! signature "public VectorInterface->asNumberCollection()"
    ##### asNumberCollection
    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Groups\NumberCollectionInterface

    description
    :   *No description available*
    
---

!!! signature "public VectorInterface->asTuple()"
    ##### asTuple
    **return**

    type
    :   Samsara\Fermat\Core\Types\Tuple

    description
    :   *No description available*
    
---

!!! signature "public VectorInterface->multiply(Samsara\Fermat\Core\Types\Decimal|Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\VectorInterface|string|int|float $value)"
    ##### multiply
    **$value**

    type
    :   Samsara\Fermat\Core\Types\Decimal|Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\VectorInterface|string|int|float

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal|Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\VectorInterface

    description
    :   *No description available*
    
---

!!! signature "public VectorInterface->multiplyScalar(Samsara\Fermat\Core\Types\Decimal $number)"
    ##### multiplyScalar
    **$number**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\VectorInterface

    description
    :   *No description available*
    
---

!!! signature "public VectorInterface->multiplyScalarProduct(Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\VectorInterface $vector)"
    ##### multiplyScalarProduct
    **$vector**

    type
    :   Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\VectorInterface

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*
    
---

!!! signature "public VectorInterface->multiplyVectorProduct(Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\VectorInterface $vector)"
    ##### multiplyVectorProduct
    **$vector**

    type
    :   Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\VectorInterface

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\VectorInterface

    description
    :   *No description available*
    
---

!!! signature "public VectorInterface->subtract(Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\VectorInterface $vector)"
    ##### subtract
    **$vector**

    type
    :   Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\VectorInterface

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\VectorInterface

    description
    :   *No description available*
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."