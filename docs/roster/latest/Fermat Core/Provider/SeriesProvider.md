# Samsara\Fermat\Provider > SeriesProvider

*No description available*


## Methods


### Static Methods

!!! signature "public SeriesProvider::maclaurinSeries(SimpleNumberInterface $input, callable $numerator, callable $exponent, callable $denominator, int $startTermAt, int $scale, int $consecutiveDivergeLimit, int $totalDivergeLimit)"
    ##### maclaurinSeries
    **$input**

    type
    :   SimpleNumberInterface

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
    :   Samsara\Fermat\Values\ImmutableDecimal

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