# Samsara\Fermat\Stats\Distribution\Continuous > Exponential

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Stats\Types\ContinuousDistribution


## Methods


### Constructor

!!! signature "public Exponential->__construct(int|float|string|Decimal $lambda)"
    ##### __construct
    **$lambda**

    type
    :   int|float|string|Decimal

    description
    :   This is the *rate parameter* not the *scale parameter*
    
    

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

    ###### __construct() Description:

    Exponential constructor.
    
---



### Instanced Methods

!!! signature "public Exponential->getMean()"
    ##### getMean
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Exponential->getMedian()"
    ##### getMedian
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Exponential->getMode()"
    ##### getMode
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Exponential->getVariance()"
    ##### getVariance
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Exponential->cdf(int|float|string|Decimal $x, int|null $scale)"
    ##### cdf
    **$x**

    type
    :   int|float|string|Decimal

    description
    :   *No description available*

    **$scale**

    type
    :   int|null

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Exponential->pdf(int|float|string|Decimal $x, int|null $scale)"
    ##### pdf
    **$x**

    type
    :   int|float|string|Decimal

    description
    :   *No description available*

    **$scale**

    type
    :   int|null

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Exponential->percentBetween(Samsara\Fermat\Core\Types\Decimal|string|int|float $x1, Samsara\Fermat\Core\Types\Decimal|string|int|float $x2, ?int $scale)"
    ##### percentBetween
    **$x1**

    type
    :   Samsara\Fermat\Core\Types\Decimal|string|int|float

    description
    :   *No description available*

    **$x2**

    type
    :   Samsara\Fermat\Core\Types\Decimal|string|int|float

    description
    :   *No description available*

    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Exponential->random()"
    ##### random
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Exponential->rangeRandom(int|float|Decimal $min, int|float|Decimal $max, int $maxIterations)"
    ##### rangeRandom
    **$min**

    type
    :   int|float|Decimal

    description
    :   *No description available*

    **$max**

    type
    :   int|float|Decimal

    description
    :   *No description available*

    **$maxIterations**

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



### Inherited Methods

!!! signature "public Distribution->randomSample(int $sampleSize)"
    ##### randomSample
    **$sampleSize**

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




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."