# Samsara\Fermat\Expressions\Values\Algebra > ExponentialFunction

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

!!! signature "public ExponentialFunction->__construct(Samsara\Fermat\Core\Values\ImmutableDecimal $exponent, Samsara\Fermat\Core\Values\ImmutableDecimal $coefficient)"
    ##### __construct
    **$exponent**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    **$coefficient**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*
    
---



### Instanced Methods

!!! signature "public ExponentialFunction->derivativeExpression()"
    ##### derivativeExpression
    **return**

    type
    :   Samsara\Fermat\Expressions\Types\Base\Interfaces\Evaluateables\FunctionInterface

    description
    :   *No description available*
    
---

!!! signature "public ExponentialFunction->describeShape()"
    ##### describeShape
    **return**

    type
    :   array

    description
    :   *No description available*
    
---

!!! signature "public ExponentialFunction->evaluateAt(Samsara\Fermat\Core\Types\Decimal|string|int|float $x)"
    ##### evaluateAt
    **$x**

    type
    :   Samsara\Fermat\Core\Types\Decimal|string|int|float

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public ExponentialFunction->integralExpression()"
    ##### integralExpression
    **return**

    type
    :   Samsara\Fermat\Expressions\Types\Base\Interfaces\Evaluateables\FunctionInterface

    description
    :   *No description available*
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."