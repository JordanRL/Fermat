# Samsara\Fermat\Core\Types\Traits > IntegerMathTrait

*No description available*


## Methods


### Instanced Methods

!!! signature "public IntegerMathTrait->getDivisors()"
    ##### getDivisors
    **return**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*

    ###### getDivisors() Description:

    Only valid for integer numbers. Returns a collection of all the integer divisors of this number.
    
---

!!! signature "public IntegerMathTrait->getGreatestCommonDivisor($num)"
    ##### getGreatestCommonDivisor
    **$num**

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*

    ###### getGreatestCommonDivisor() Description:

    Only valid for integer numbers. Returns the greatest common divisor for this number and the supplied number.
    
---

!!! signature "public IntegerMathTrait->getLeastCommonMultiple($num)"
    ##### getLeastCommonMultiple
    **$num**

    description
    :   
    
    

    **return**

    type
    :   static

    description
    :   *No description available*

    ###### getLeastCommonMultiple() Description:

    Only valid for integer numbers. Returns the least common multiple of this number and the supplied number.
    
---

!!! signature "public IntegerMathTrait->getPrimeFactors()"
    ##### getPrimeFactors
    **return**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

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

    Only valid for integer numbers. Uses the Miller-Rabin probabilistic primality test. The default "certainty" value of 20 results in a false-positive rate of 1 in 1.10 x 10^12.
    
     With high enough certainty values, the probability that the program returned an incorrect result due to errors in the computer hardware begins to dominate. Typically, a certainty of around 40 is sufficient for a prime number used in a cryptographic context.
    
---

!!! signature "public IntegerMathTrait->doubleFactorial()"
    ##### doubleFactorial
    **return**

    type
    :   static

    description
    :   *No description available*

    ###### doubleFactorial() Description:

    Only valid for integer numbers. Takes the double factorial of this number. Not to be confused with taking the factorial twice which is (n!)!, the double factorial n!! multiplies all the numbers between 1 and n that share the same parity (odd or even).
    
     For more information, see: https://mathworld.wolfram.com/DoubleFactorial.html
    
---

!!! signature "public IntegerMathTrait->factorial()"
    ##### factorial
    **return**

    type
    :   static

    description
    :   *No description available*

    ###### factorial() Description:

    Only valid for integer numbers. Takes the factorial of this number. The factorial is every number between 1 and this number multiplied together.
    
---

!!! signature "public IntegerMathTrait->fallingFactorial(Samsara\Fermat\Core\Types\Decimal|string|int|float $num)"
    ##### fallingFactorial
    **$num**

    type
    :   Samsara\Fermat\Core\Types\Decimal|string|int|float

    description
    :   *No description available*

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public IntegerMathTrait->risingFactorial(Samsara\Fermat\Core\Types\Decimal|string|int|float $num)"
    ##### risingFactorial
    **$num**

    type
    :   Samsara\Fermat\Core\Types\Decimal|string|int|float

    description
    :   *No description available*

    **return**

    type
    :   static

    description
    :   *No description available*
    
---

!!! signature "public IntegerMathTrait->semiFactorial()"
    ##### semiFactorial
    **return**

    type
    :   static

    description
    :   *No description available*

    ###### semiFactorial() Description:

    Alias for doubleFactorial().
    
---

!!! signature "public IntegerMathTrait->subFactorial()"
    ##### subFactorial
    **return**

    type
    :   static

    description
    :   *No description available*

    ###### subFactorial() Description:

    Only valid for integer numbers. Takes the subfactorial of this number. The subfactorial is the number of derangements of a set with n members.
    
     For more information, see: https://mathworld.wolfram.com/Subfactorial.html
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."