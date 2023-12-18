# Samsara\Fermat\Stats\Distribution\Discrete > Poisson

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Stats\Types\DiscreteDistribution


## Methods


### Constructor

!!! signature "public Poisson->__construct(int|float|string|Decimal $lambda)"
    ##### __construct
    **$lambda**

    type
    :   int|float|string|Decimal

    description
    :   
    
    

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

    ###### __construct() Description:

    Poisson constructor.
    
---



### Instanced Methods

!!! signature "public Poisson->getMean()"
    ##### getMean
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Poisson->getMedian()"
    ##### getMedian
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Poisson->getMode()"
    ##### getMode
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Poisson->getVariance()"
    ##### getVariance
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Poisson->cdf(int|float|string|Decimal $x, int $scale)"
    ##### cdf
    **$x**

    type
    :   int|float|string|Decimal

    description
    :   *No description available*

    **$scale**

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

!!! signature "public Poisson->pmf(int|float|string|Decimal $k, int $scale)"
    ##### pmf
    **$k**

    type
    :   int|float|string|Decimal

    description
    :   *No description available*

    **$scale**

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

!!! signature "public Poisson->probabilityOfKEvents(int|float|string|Decimal $k, int $scale)"
    ##### probabilityOfKEvents
    **$k**

    type
    :   int|float|string|Decimal

    description
    :   *No description available*

    **$scale**

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

!!! signature "public Poisson->random()"
    ##### random
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Poisson->rangePmf(int|float|string|Decimal $k1, int|float|string|Decimal $k2)"
    ##### rangePmf
    **$k1**

    type
    :   int|float|string|Decimal

    description
    :   *No description available*

    **$k2**

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

!!! signature "public Poisson->rangeRandom(int|float|Decimal $min, int|float|Decimal $max, int $maxIterations)"
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

    ###### rangeRandom() Description:

    WARNING: This function is of very limited use with Poisson distributions, and may represent a SIGNIFICANT performance hit for certain values of $min, $max, $lambda, and $maxIterations
    
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