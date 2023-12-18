# Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups > MatrixInterface

*No description available*


## Methods


### Instanced Methods

!!! signature "public MatrixInterface->add(Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\MatrixInterface $value)"
    ##### add
    **$value**

    type
    :   Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\MatrixInterface

    description
    :   *No description available*

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->addScalarAsI(Samsara\Fermat\Core\Types\Decimal $value)"
    ##### addScalarAsI
    **$value**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->addScalarAsJ(Samsara\Fermat\Core\Types\Decimal $value)"
    ##### addScalarAsJ
    **$value**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->getColumn(int $column)"
    ##### getColumn
    **$column**

    type
    :   int

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->getColumnCount()"
    ##### getColumnCount
    **return**

    type
    :   int

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->getDeterminant()"
    ##### getDeterminant
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->getInverseMatrix()"
    ##### getInverseMatrix
    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->getRow(int $row)"
    ##### getRow
    **$row**

    type
    :   int

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->getRowCount()"
    ##### getRowCount
    **return**

    type
    :   int

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->isSquare()"
    ##### isSquare
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->multiply($value)"
    ##### multiply
    **$value**

    description
    :   *No description available*

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->popColumn()"
    ##### popColumn
    **return**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->popRow()"
    ##### popRow
    **return**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->pushColumn(Samsara\Fermat\Core\Types\NumberCollection $column)"
    ##### pushColumn
    **$column**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->pushRow(Samsara\Fermat\Core\Types\NumberCollection $row)"
    ##### pushRow
    **$row**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->rotate(bool $clockwise)"
    ##### rotate
    **$clockwise**

    type
    :   bool

    description
    :   *No description available*

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->shiftColumn()"
    ##### shiftColumn
    **return**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->shiftRow()"
    ##### shiftRow
    **return**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->subtract(Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\MatrixInterface $value)"
    ##### subtract
    **$value**

    type
    :   Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups\MatrixInterface

    description
    :   *No description available*

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->subtractScalarAsI(Samsara\Fermat\Core\Types\Decimal $value)"
    ##### subtractScalarAsI
    **$value**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->subtractScalarAsJ(Samsara\Fermat\Core\Types\Decimal $value)"
    ##### subtractScalarAsJ
    **$value**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->unshiftColumn(Samsara\Fermat\Core\Types\NumberCollection $column)"
    ##### unshiftColumn
    **$column**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public MatrixInterface->unshiftRow(Samsara\Fermat\Core\Types\NumberCollection $row)"
    ##### unshiftRow
    **$row**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*

    **return**

    type
    :   static

    description
    :   *No description available*
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."