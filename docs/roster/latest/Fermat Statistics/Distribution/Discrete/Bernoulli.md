# Samsara\Fermat\Stats\Distribution\Discrete > Bernoulli

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Stats\Types\DiscreteDistribution


## Methods


### Constructor

!!! signature "public Bernoulli->__construct(Samsara\Fermat\Core\Types\Decimal|string|int|float $p)"
    ##### __construct
    **$p**

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

!!! signature "public Bernoulli->getMean()"
    ##### getMean
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Bernoulli->getMedian()"
    ##### getMedian
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Bernoulli->getMode()"
    ##### getMode
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Bernoulli->getVariance()"
    ##### getVariance
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Bernoulli->cdf(Samsara\Fermat\Core\Types\Decimal|string|int|float $k, int $scale)"
    ##### cdf
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

!!! signature "public Bernoulli->pmf(Samsara\Fermat\Core\Types\Decimal|string|int|float $k, int $scale)"
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

!!! signature "public Bernoulli->random()"
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