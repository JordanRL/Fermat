# Samsara\Fermat\LinearAlgebra\Types > Matrix

*No description available*


## Inheritance


### Implements

!!! signature interface "MatrixInterface"
    ##### MatrixInterface
    namespace
    :   Samsara\Fermat\LinearAlgebra\Types\Base\Interfaces\Groups

    description
    :   

    *No description available*



### Has Traits

!!! signature trait "ShapeTrait"
    ##### ShapeTrait
    namespace
    :   Samsara\Fermat\LinearAlgebra\Types\Traits\Matrix

    description
    :   

    *No description available*

!!! signature trait "DirectAccessTrait"
    ##### DirectAccessTrait
    namespace
    :   Samsara\Fermat\LinearAlgebra\Types\Traits\Matrix

    description
    :   

    *No description available*



## Variables & Data


### Class Constants

!!! signature constant "Matrix::MODE_ROWS_INPUT"
    ##### MODE_ROWS_INPUT
    value
    :   'rows'

!!! signature constant "Matrix::MODE_COLUMNS_INPUT"
    ##### MODE_COLUMNS_INPUT
    value
    :   'columns'



## Methods


### Constructor

!!! signature "public Matrix->__construct(NumberCollection[] $data, string $mode)"
    ##### __construct
    **$data**

    type
    :   NumberCollection[]

    description
    :   *No description available*

    **$mode**

    type
    :   string

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

    ###### __construct() Description:

    Matrix constructor. The array of number collections can be an array of rows, or an array of columns. Default is rows.
    
---



### Instanced Methods

!!! signature "public Matrix->getDeterminant()"
    ##### getDeterminant
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Matrix->getInverseMatrix()"
    ##### getInverseMatrix
    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public Matrix->getMatrixOfMinors()"
    ##### getMatrixOfMinors
    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public Matrix->add(MatrixInterface $value)"
    ##### add
    **$value**

    type
    :   MatrixInterface

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public Matrix->addScalarAsI(Number $value)"
    ##### addScalarAsI
    **$value**

    type
    :   Number

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*

    ###### addScalarAsI() Description:

    This function takes an input scalar value and multiplies an identity matrix by that scalar, then does matrix addition with the resulting matrix.
    
---

!!! signature "public Matrix->addScalarAsJ(Number $value)"
    ##### addScalarAsJ
    **$value**

    type
    :   Number

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*

    ###### addScalarAsJ() Description:

    This function takes a scalar input value and adds that value to each position in the matrix directly.
    
---

!!! signature "public Matrix->applyAlternatingSigns()"
    ##### applyAlternatingSigns
    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public Matrix->childMatrix(int $excludeRow, int $excludeColumn, bool $forceNewMatrix)"
    ##### childMatrix
    **$excludeRow**

    type
    :   int

    description
    :   *No description available*

    **$excludeColumn**

    type
    :   int

    description
    :   *No description available*

    **$forceNewMatrix**

    type
    :   bool

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*

    ###### childMatrix() Description:

    This function returns a subset of the current matrix as a new matrix with one row and one column removed from the dataset.
    
---

!!! signature "public Matrix->mapFuction(callable $fn)"
    ##### mapFuction
    **$fn**

    type
    :   callable

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public Matrix->multiply($value)"
    ##### multiply
    **$value**

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public Matrix->subtract(MatrixInterface $value)"
    ##### subtract
    **$value**

    type
    :   MatrixInterface

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public Matrix->subtractScalarAsI(Decimal $value)"
    ##### subtractScalarAsI
    **$value**

    type
    :   Decimal

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public Matrix->subtractScalarAsJ(Decimal $value)"
    ##### subtractScalarAsJ
    **$value**

    type
    :   Decimal

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public Matrix->getAdjoint()"
    ##### getAdjoint
    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public Matrix->getAdjugate()"
    ##### getAdjugate
    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public Matrix->getColumn(int $column)"
    ##### getColumn
    **$column**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*
    
---

!!! signature "public Matrix->getColumnCount()"
    ##### getColumnCount
    **return**

    type
    :   int

    description
    :   *No description available*
    
---

!!! signature "public Matrix->getRow(int $row)"
    ##### getRow
    **$row**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*
    
---

!!! signature "public Matrix->getRowCount()"
    ##### getRowCount
    **return**

    type
    :   int

    description
    :   *No description available*
    
---

!!! signature "public Matrix->isSquare()"
    ##### isSquare
    **return**

    type
    :   bool

    description
    :   *No description available*
    
---

!!! signature "public Matrix->rotate(bool $clockwise)"
    ##### rotate
    **$clockwise**

    type
    :   bool

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public Matrix->transpose()"
    ##### transpose
    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public Matrix->popColumn()"
    ##### popColumn
    **return**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*
    
---

!!! signature "public Matrix->popRow()"
    ##### popRow
    **return**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*
    
---

!!! signature "public Matrix->pushColumn(NumberCollection $column)"
    ##### pushColumn
    **$column**

    type
    :   NumberCollection

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public Matrix->pushRow(NumberCollection $row)"
    ##### pushRow
    **$row**

    type
    :   NumberCollection

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public Matrix->shiftColumn()"
    ##### shiftColumn
    **return**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*
    
---

!!! signature "public Matrix->shiftRow()"
    ##### shiftRow
    **return**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*
    
---

!!! signature "public Matrix->unshiftColumn(NumberCollection $column)"
    ##### unshiftColumn
    **$column**

    type
    :   NumberCollection

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public Matrix->unshiftRow(NumberCollection $row)"
    ##### unshiftRow
    **$row**

    type
    :   NumberCollection

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."