# Samsara\Fermat\Stats\Types > ContinuousDistribution

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Stats\Types\Distribution


## Methods


### Instanced Methods

!!! signature "public ContinuousDistribution->percentBetween(int|float|string|Decimal $x1, int|float|string|Decimal $x2, int|null $scale)"
    ##### percentBetween
    **$x1**

    type
    :   int|float|string|Decimal

    description
    :   *No description available*

    **$x2**

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

!!! signature "public ContinuousDistribution->pdf(Samsara\Fermat\Core\Types\Decimal|string|int|float $x, ?int $scale)"
    ##### pdf
    **$x**

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

!!! signature "public Distribution->rangeRandom(int|float|string|Decimal $min, int|float|string|Decimal $max, int $maxIterations)"
    ##### rangeRandom
    **$min**

    type
    :   int|float|string|Decimal

    description
    :   *No description available*

    **$max**

    type
    :   int|float|string|Decimal

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

!!! signature "public Distribution->cdf(int|float|string|Decimal $x, int $scale)"
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

!!! signature "public Distribution->getMean()"
    ##### getMean
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Distribution->getMedian()"
    ##### getMedian
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Distribution->getMode()"
    ##### getMode
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Distribution->getVariance()"
    ##### getVariance
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Distribution->random()"
    ##### random
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."