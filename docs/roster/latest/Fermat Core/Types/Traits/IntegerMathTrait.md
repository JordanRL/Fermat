# Samsara\Fermat\Core\Types\Traits > IntegerMathTrait

*No description available*


## Methods


### Instanced Methods

!!! signature "public IntegerMathTrait->factorial()"
    ##### factorial
    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public IntegerMathTrait->subFactorial()"
    ##### subFactorial
    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public IntegerMathTrait->doubleFactorial()"
    ##### doubleFactorial
    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public IntegerMathTrait->semiFactorial()"
    ##### semiFactorial
    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public IntegerMathTrait->getLeastCommonMultiple($num)"
    ##### getLeastCommonMultiple
    **$num**

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public IntegerMathTrait->getGreatestCommonDivisor($num)"
    ##### getGreatestCommonDivisor
    **$num**

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Core\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*
    
---

!!! signature "public IntegerMathTrait->isPrime(int|null $certainty)"
    ##### isPrime
    **$certainty**

    type
    :   int|null

    description
    :   The certainty level desired. False positive rate = 1 in 4^$certainty.

    **return**

    type
    :   bool

    description
    :   *No description available*

    ###### isPrime() Description:

    This function is a PHP implementation of the Miller-Rabin primality test. The default "certainty" value of 20 results in a false-positive rate of 1 in 1.10 x 10^12.
    
     Presumably, the probability of your hardware failing while this code is running is higher, meaning this should be statistically as certain as a deterministic algorithm on normal computer hardware.
    
---

!!! signature "public IntegerMathTrait->getDivisors()"
    ##### getDivisors
    **return**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*
    
---

!!! signature "public IntegerMathTrait->asPrimeFactors()"
    ##### asPrimeFactors
    **return**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."