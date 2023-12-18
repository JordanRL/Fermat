# Samsara\Fermat\Core\Types\Traits > SimpleArithmeticTrait

*No description available*


## Inheritance


### Has Traits

!!! signature trait "ArithmeticSelectionTrait"
    ##### ArithmeticSelectionTrait
    namespace
    :   Samsara\Fermat\Core\Types\Base\Traits

    description
    :   

    *No description available*

!!! signature trait "ArithmeticScaleTrait"
    ##### ArithmeticScaleTrait
    namespace
    :   Samsara\Fermat\Core\Types\Base\Traits

    description
    :   

    *No description available*

!!! signature trait "ArithmeticNativeTrait"
    ##### ArithmeticNativeTrait
    namespace
    :   Samsara\Fermat\Core\Types\Base\Traits

    description
    :   

    *No description available*

!!! signature trait "ArithmeticGMPTrait"
    ##### ArithmeticGMPTrait
    namespace
    :   Samsara\Fermat\Core\Types\Base\Traits

    description
    :   

    *No description available*

!!! signature trait "ArithmeticHelperSimpleTrait"
    ##### ArithmeticHelperSimpleTrait
    namespace
    :   Samsara\Fermat\Core\Types\Base\Traits

    description
    :   

    *No description available*



## Methods


### Instanced Methods

!!! signature "public SimpleArithmeticTrait->add(string|int|float|Decimal|Fraction|ComplexNumber $num)"
    ##### add
    **$num**

    type
    :   string|int|float|Decimal|Fraction|ComplexNumber

    description
    :   The number you are adding to this number
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\MutableDecimal|Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Complex\Values\MutableComplexNumber|Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Core\Values\MutableFraction|Samsara\Fermat\Core\Values\ImmutableFraction|static

    description
    :   *No description available*

    ###### add() Description:

    Adds a number to this number. Works (to the degree that math allows it to work) for all classes that extend the Number abstract class.
    
---

!!! signature "public SimpleArithmeticTrait->divide(string|int|float|Decimal|Fraction|ComplexNumber $num, int|null $scale)"
    ##### divide
    **$num**

    type
    :   string|int|float|Decimal|Fraction|ComplexNumber

    description
    :   The number you dividing this number by

    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the division. Leave null to use this object's scale.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Core\Values\ImmutableFraction|static

    description
    :   *No description available*

    ###### divide() Description:

    Divides this number by a number. Works (to the degree that math allows it to work) for all classes that extend the Number abstract class.
    
---

!!! signature "public SimpleArithmeticTrait->multiply(string|int|float|Decimal|Fraction|ComplexNumber $num)"
    ##### multiply
    **$num**

    type
    :   string|int|float|Decimal|Fraction|ComplexNumber

    description
    :   The number you are multiplying with this number
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\MutableDecimal|Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Complex\Values\MutableComplexNumber|Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Core\Values\MutableFraction|Samsara\Fermat\Core\Values\ImmutableFraction|static

    description
    :   *No description available*

    ###### multiply() Description:

    Multiplies a number with this number. Works (to the degree that math allows it to work) for all classes that extend the Number abstract class.
    
---

!!! signature "public SimpleArithmeticTrait->pow(string|int|float|Decimal|Fraction|ComplexNumber $num)"
    ##### pow
    **$num**

    type
    :   string|int|float|Decimal|Fraction|ComplexNumber

    description
    :   The exponent to raise the number to
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\MutableDecimal|Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Complex\Values\MutableComplexNumber|Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Core\Values\MutableFraction|Samsara\Fermat\Core\Values\ImmutableFraction|static

    description
    :   *No description available*

    ###### pow() Description:

    Raises this number to the power of a number. Works (to the degree that math allows it to work) for all classes that extend the Number abstract class.
    
---

!!! signature "public SimpleArithmeticTrait->sqrt(int|null $scale)"
    ##### sqrt
    **$scale**

    type
    :   int|null

    description
    :   The number of digits you want to return from the operation. Leave null to use this object's scale.
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\MutableDecimal|Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Complex\Values\MutableComplexNumber|Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Core\Values\MutableFraction|Samsara\Fermat\Core\Values\ImmutableFraction|static

    description
    :   *No description available*

    ###### sqrt() Description:

    Takes the (positive) square root of this number. Works (to the degree that math allows it to work) for all classes that extend the Number abstract class.
    
---

!!! signature "public SimpleArithmeticTrait->subtract(string|int|float|Decimal|Fraction|ComplexNumber $num)"
    ##### subtract
    **$num**

    type
    :   string|int|float|Decimal|Fraction|ComplexNumber

    description
    :   The number you are subtracting from this number
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\MutableDecimal|Samsara\Fermat\Core\Values\ImmutableDecimal|Samsara\Fermat\Complex\Values\MutableComplexNumber|Samsara\Fermat\Complex\Values\ImmutableComplexNumber|Samsara\Fermat\Core\Values\MutableFraction|Samsara\Fermat\Core\Values\ImmutableFraction|static

    description
    :   *No description available*

    ###### subtract() Description:

    Subtracts a number from this number. Works (to the degree that math allows it to work) for all classes that extend the Number abstract class.
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."