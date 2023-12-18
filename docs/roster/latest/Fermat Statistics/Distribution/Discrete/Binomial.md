# Samsara\Fermat\Stats\Distribution\Discrete > Binomial

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Stats\Types\DiscreteDistribution


## Methods


### Constructor

!!! signature "public Binomial->__construct(Samsara\Fermat\Core\Types\Decimal|string|int|float $p, Samsara\Fermat\Core\Types\Decimal|string|int|float $n)"
    ##### __construct
    **$p**

    type
    :   Samsara\Fermat\Core\Types\Decimal|string|int|float

    description
    :   *No description available*

    **$n**

    type
    :   Samsara\Fermat\Core\Types\Decimal|string|int|float

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*
    
---



### Instanced Methods

!!! signature "public Binomial->getMean()"
    ##### getMean
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Binomial->getMedian()"
    ##### getMedian
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Binomial->getMode()"
    ##### getMode
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Binomial->getVariance()"
    ##### getVariance
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Binomial->cdf(Samsara\Fermat\Core\Types\Decimal|string|int|float $x, int $scale)"
    ##### cdf
    **$x**

    type
    :   Samsara\Fermat\Core\Types\Decimal|string|int|float

    description
    :   *No description available*

    **$scale**

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

!!! signature "public Binomial->pmf(Samsara\Fermat\Core\Types\Decimal|string|int|float $k, int $scale)"
    ##### pmf
    **$k**

    type
    :   Samsara\Fermat\Core\Types\Decimal|string|int|float

    description
    :   *No description available*

    **$scale**

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

!!! signature "public Binomial->random()"
    ##### random
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




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."