# Samsara\Fermat\Core\Provider > SeriesProvider

*No description available*


## Variables & Data


### Class Constants

!!! signature constant "SeriesProvider::SUM_MODE_ADD"
    ##### SUM_MODE_ADD
    value
    :   1

!!! signature constant "SeriesProvider::SUM_MODE_SUB"
    ##### SUM_MODE_SUB
    value
    :   2

!!! signature constant "SeriesProvider::SUM_MODE_ALT_ADD"
    ##### SUM_MODE_ALT_ADD
    value
    :   3

!!! signature constant "SeriesProvider::SUM_MODE_ALT_SUB"
    ##### SUM_MODE_ALT_SUB
    value
    :   4

!!! signature constant "SeriesProvider::SUM_MODE_ALT_FIRST_ADD"
    ##### SUM_MODE_ALT_FIRST_ADD
    value
    :   5

!!! signature constant "SeriesProvider::SUM_MODE_ALT_FIRST_SUB"
    ##### SUM_MODE_ALT_FIRST_SUB
    value
    :   6



## Methods


### Static Methods

!!! signature "public SeriesProvider::generalizedContinuedFraction(ContinuedFractionTermInterface $aPart, ContinuedFractionTermInterface $bPart, int $terms, int $scale, int $sumMode)"
    ##### generalizedContinuedFraction
    **$aPart**

    type
    :   ContinuedFractionTermInterface

    description
    :   *No description available*

    **$bPart**

    type
    :   ContinuedFractionTermInterface

    description
    :   *No description available*

    **$terms**

    type
    :   int

    description
    :   *No description available*

    **$scale**

    type
    :   int

    description
    :   *No description available*

    **$sumMode**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    ###### generalizedContinuedFraction() Description:

    This function processes a generalized continued fraction. In order to use this you must provide two callable classes that implement the ContinuedFractionTermInterface. This interface defines the expected inputs and outputs of the callable used by this function.
    
     This function evaluates continued fractions in the form:
    
     b0 + (a1 / (b1 + (a2 / (b2 + (a3 / b3 + ...)))))
    
     This is a continued fraction in the form used in complex analysis, referred to as a generalized continued fraction.
    
     For more information about this, please read the wikipedia article on the subject:
    
     https://en.wikipedia.org/wiki/Generalized_continued_fraction](https://en.wikipedia.org/wiki/Generalized_continued_fraction)

---

!!! signature "public SeriesProvider::genericInfiniteProduct(callable $termFunction, int $scale, int $startAt)"
    ##### genericInfiniteProduct
    **$termFunction**

    type
    :   callable

    description
    :   *No description available*

    **$scale**

    type
    :   int

    description
    :   *No description available*

    **$startAt**

    type
    :   int

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public SeriesProvider::genericInfiniteSum(callable $termFunction, int $scale, int $startAt)"
    ##### genericInfiniteSum
    **$termFunction**

    type
    :   callable

    description
    :   *No description available*

    **$scale**

    type
    :   int

    description
    :   *No description available*

    **$startAt**

    type
    :   int

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public SeriesProvider::maclaurinSeries(Decimal $input, callable $numerator, callable $exponent, callable $denominator, int $startTermAt, int $scale, int $consecutiveDivergeLimit, int $totalDivergeLimit)"
    ##### maclaurinSeries
    **$input**

    type
    :   Decimal

    description
    :   *No description available*

    **$numerator**

    type
    :   callable

    description
    :   *No description available*

    **$exponent**

    type
    :   callable

    description
    :   *No description available*

    **$denominator**

    type
    :   callable

    description
    :   *No description available*

    **$startTermAt**

    type
    :   int

    description
    :   *No description available*

    **$scale**

    type
    :   int

    description
    :   *No description available*

    **$consecutiveDivergeLimit**

    type
    :   int

    description
    :   *No description available*

    **$totalDivergeLimit**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    ###### maclaurinSeries() Description:

    Creates a series that evaluates the following:
    
     SUM[$startTerm -> infinity]( numerator($n) × $input^$exponent($n)
    
     denominator($n)
    
    
    
     Where $n is the current term number, starting at $startTerm, and increasing by 1 each loop; where $numerator, exponent, and $denominator are callables that take the term number (as an int) as their only input, and give the value of that section at that term number; and where $input is the x value being considered for the series.
    
     The function continues adding terms until a term has MORE leading zeros than the $scale setting. (That is, until it adds zero to the total when considering significant digits.)

---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."