# Samsara\Fermat\Core\Values\Distribution > Normal

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Core\Types\Distribution


## Methods


### Constructor

!!! signature "public Normal->__construct(int|float|DecimalInterface $mean, int|float|DecimalInterface $sd)"
    ##### __construct
    **$mean**

    type
    :   int|float|DecimalInterface

    description
    :   *No description available*

    **$sd**

    type
    :   int|float|DecimalInterface

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

!!! signature "public Normal::makeFromMean(int|float|DecimalInterface $p, int|float|DecimalInterface $x, int|float|DecimalInterface $mean)"
    ##### makeFromMean
    **$p**

    type
    :   int|float|DecimalInterface

    description
    :   *No description available*

    **$x**

    type
    :   int|float|DecimalInterface

    description
    :   *No description available*

    **$mean**

    type
    :   int|float|DecimalInterface

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\Distribution\Normal

    description
    :   *No description available*

---

!!! signature "public Normal::makeFromSd(int|float|DecimalInterface $p, int|float|DecimalInterface $x, int|float|DecimalInterface $sd)"
    ##### makeFromSd
    **$p**

    type
    :   int|float|DecimalInterface

    description
    :   *No description available*

    **$x**

    type
    :   int|float|DecimalInterface

    description
    :   *No description available*

    **$sd**

    type
    :   int|float|DecimalInterface

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\Distribution\Normal

    description
    :   *No description available*

---



### Instanced Methods

!!! signature "public Normal->getSD()"
    ##### getSD
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Normal->getMean()"
    ##### getMean
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Normal->evaluateAt($x, int $scale)"
    ##### evaluateAt
    **$x**

    description
    :   
    
    

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

!!! signature "public Normal->cdf(int|float|DecimalInterface $x)"
    ##### cdf
    **$x**

    type
    :   int|float|DecimalInterface

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Normal->pdf(int|float|DecimalInterface $x1, null|int|float|DecimalInterface $x2)"
    ##### pdf
    **$x1**

    type
    :   int|float|DecimalInterface

    description
    :   *No description available*

    **$x2**

    type
    :   null|int|float|DecimalInterface

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Normal->cdfProduct(FunctionInterface $function, $x)"
    ##### cdfProduct
    **$function**

    type
    :   FunctionInterface

    description
    :   *No description available*

    **$x**

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Normal->pdfProduct(FunctionInterface $function, $x1, $x2)"
    ##### pdfProduct
    **$function**

    type
    :   FunctionInterface

    description
    :   *No description available*

    **$x1**

    description
    :   *No description available*

    **$x2**

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Normal->percentBelowX(int|float|DecimalInterface $x)"
    ##### percentBelowX
    **$x**

    type
    :   int|float|DecimalInterface

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Normal->percentAboveX(int|float|DecimalInterface $x)"
    ##### percentAboveX
    **$x**

    type
    :   int|float|DecimalInterface

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Normal->zScoreOfX(int|float|DecimalInterface $x)"
    ##### zScoreOfX
    **$x**

    type
    :   int|float|DecimalInterface

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Normal->xFromZScore(int|float|DecimalInterface $z)"
    ##### xFromZScore
    **$z**

    type
    :   int|float|DecimalInterface

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

!!! signature "public Normal->rangeRandom(int|float|NumberInterface $min, int|float|NumberInterface $max, int $maxIterations)"
    ##### rangeRandom
    **$min**

    type
    :   int|float|NumberInterface

    description
    :   *No description available*

    **$max**

    type
    :   int|float|NumberInterface

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
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Core\Types\NumberCollection

    description
    :   *No description available*
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."