# Samsara\Fermat\Stats\Distribution\Continuous > Normal

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Stats\Types\ContinuousDistribution


## Methods


### Constructor

!!! signature "public Normal->__construct(int|float|string|Decimal $mean, int|float|string|Decimal $standardDeviation)"
    ##### __construct
    **$mean**

    type
    :   int|float|string|Decimal

    description
    :   *No description available*

    **$standardDeviation**

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

    Normal constructor.
    
---



### Static Methods

!!! signature "public Normal::makeFromMean(int|float|string|Decimal $p, int|float|string|Decimal $x, int|float|string|Decimal $mean)"
    ##### makeFromMean
    **$p**

    type
    :   int|float|string|Decimal

    description
    :   *No description available*

    **$x**

    type
    :   int|float|string|Decimal

    description
    :   *No description available*

    **$mean**

    type
    :   int|float|string|Decimal

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Stats\Distribution\Continuous\Normal

    description
    :   *No description available*

---

!!! signature "public Normal::makeFromSd(int|float|string|Decimal $p, int|float|string|Decimal $x, int|float|string|Decimal $sd)"
    ##### makeFromSd
    **$p**

    type
    :   int|float|string|Decimal

    description
    :   *No description available*

    **$x**

    type
    :   int|float|string|Decimal

    description
    :   *No description available*

    **$sd**

    type
    :   int|float|string|Decimal

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Stats\Distribution\Continuous\Normal

    description
    :   *No description available*

---



### Instanced Methods

!!! signature "public Normal->getMean()"
    ##### getMean
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Normal->getMedian()"
    ##### getMedian
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Normal->getMode()"
    ##### getMode
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Normal->getSD()"
    ##### getSD
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Normal->getVariance()"
    ##### getVariance
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Normal->cdf(int|float|string|Decimal $x, int|null $scale)"
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

!!! signature "public Normal->pdf(int|float|string|Decimal $x, int|null $scale)"
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

!!! signature "public Normal->percentAboveX(int|float|string|Decimal $x)"
    ##### percentAboveX
    **$x**

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

!!! signature "public Normal->percentBelowX(int|float|string|Decimal $x)"
    ##### percentBelowX
    **$x**

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

!!! signature "public Normal->random()"
    ##### random
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Normal->xFromZScore(int|float|string|Decimal $z)"
    ##### xFromZScore
    **$z**

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

!!! signature "public Normal->zScoreOfX(int|float|string|Decimal $x)"
    ##### zScoreOfX
    **$x**

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



### Inherited Methods

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