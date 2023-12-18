# Samsara\Fermat\Expressions\Values\Algebra > PolynomialFunction

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Expressions\Types\Expression


### Implements

!!! signature interface "ExpressionInterface"
    ##### ExpressionInterface
    namespace
    :   Samsara\Fermat\Expressions\Types\Base\Interfaces\Evaluateables

    description
    :   

    *No description available*

!!! signature interface "FunctionInterface"
    ##### FunctionInterface
    namespace
    :   Samsara\Fermat\Expressions\Types\Base\Interfaces\Evaluateables

    description
    :   

    *No description available*



## Methods


### Constructor

!!! signature "public PolynomialFunction->__construct(array|NumberCollection $coefficients)"
    ##### __construct
    **$coefficients**

    type
    :   array|NumberCollection

    description
    :   
    
    

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

    ###### __construct() Description:

    PolynomialFunction constructor.
    
---



### Static Methods

!!! signature "public PolynomialFunction::createFromFoil(int[]|float[]|Decimal[] $group1, int[]|float[]|Decimal[] $group2)"
    ##### createFromFoil
    **$group1**

    type
    :   int[]|float[]|Decimal[]

    description
    :   *No description available*

    **$group2**

    type
    :   int[]|float[]|Decimal[]

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Expressions\Values\Algebra\PolynomialFunction

    description
    :   *No description available*

    ###### createFromFoil() Description:

    This function performs a FOIL expansion on a list of parameters.
    
     Assumptions: The coefficients are the numbers provided in the arrays The coefficients are listed in descending order of their exponent on the function variable. For example, if you were multiplying (2 + 3x)*(5 - 1x^2 + 1x), it would expect these inputs:
    
     If not all exponents are used continuously, a zero must be provided for the position that is skipped. For example, if one of the provided groups was 4x^2 + 2, it would expect: [4, 0, 2]

---



### Instanced Methods

!!! signature "public PolynomialFunction->derivativeExpression()"
    ##### derivativeExpression
    **return**

    type
    :   Samsara\Fermat\Expressions\Types\Base\Interfaces\Evaluateables\FunctionInterface

    description
    :   *No description available*
    
---

!!! signature "public PolynomialFunction->describeShape()"
    ##### describeShape
    **return**

    type
    :   array

    description
    :   *No description available*
    
---

!!! signature "public PolynomialFunction->evaluateAt(int|float|string|Decimal $x)"
    ##### evaluateAt
    **$x**

    type
    :   int|float|string|Decimal

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public PolynomialFunction->integralExpression(float|int|string|Decimal $C)"
    ##### integralExpression
    **$C**

    type
    :   float|int|string|Decimal

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Expressions\Types\Base\Interfaces\Evaluateables\FunctionInterface

    description
    :   *No description available*
    
---

!!! signature "public PolynomialFunction->multiplyByPolynomial(Samsara\Fermat\Expressions\Values\Algebra\PolynomialFunction $polynomialFunction)"
    ##### multiplyByPolynomial
    **$polynomialFunction**

    type
    :   Samsara\Fermat\Expressions\Values\Algebra\PolynomialFunction

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."