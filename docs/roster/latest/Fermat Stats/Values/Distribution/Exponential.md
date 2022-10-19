# Samsara\Fermat\Core\Values\Distribution > Exponential

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Core\Types\Distribution


## Methods


### Constructor

!!! signature "public Exponential->__construct(int|float|DecimalInterface $lambda)"
    ##### __construct
    **$lambda**

    type
    :   int|float|DecimalInterface

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

!!! signature "public Exponential->cdf(int|float|DecimalInterface $x, int $scale)"
    ##### cdf
    **$x**

    type
    :   int|float|DecimalInterface

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

!!! signature "public Exponential->pdf($x, int $scale)"
    ##### pdf
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

!!! signature "public Exponential->rangePdf($x1, $x2, int $scale)"
    ##### rangePdf
    **$x1**

    description
    :   *No description available*

    **$x2**

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

!!! signature "public Exponential->random()"
    ##### random
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Exponential->rangeRandom(int|float|DecimalInterface $min, int|float|DecimalInterface $max, int $maxIterations)"
    ##### rangeRandom
    **$min**

    type
    :   int|float|DecimalInterface

    description
    :   *No description available*

    **$max**

    type
    :   int|float|DecimalInterface

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