# Samsara\Fermat\Values\Distribution > Poisson

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Types\Distribution


## Methods


### Constructor

!!! signature "public Poisson->__construct(int|float|DecimalInterface $lambda)"
    ##### __construct
    **$lambda**

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

    Poisson constructor.
    
---



### Instanced Methods

!!! signature "public Poisson->probabilityOfKEvents(int|float|DecimalInterface $k, int $scale)"
    ##### probabilityOfKEvents
    **$k**

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
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Poisson->cdf(int|float|DecimalInterface $x, int $scale)"
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
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Poisson->pmf(float|int|DecimalInterface $x, int $scale)"
    ##### pmf
    **$x**

    type
    :   float|int|DecimalInterface

    description
    :   
    
    

    **$scale**

    type
    :   int

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Poisson->rangePmf(int|float|DecimalInterface $x1, int|float|DecimalInterface $x2)"
    ##### rangePmf
    **$x1**

    type
    :   int|float|DecimalInterface

    description
    :   *No description available*

    **$x2**

    type
    :   int|float|DecimalInterface

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Poisson->random()"
    ##### random
    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Poisson->rangeRandom(int|float|NumberInterface $min, int|float|NumberInterface $max, int $maxIterations)"
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
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

    ###### rangeRandom() Description:

    WARNING: This function is of very limited use with Poisson distributions, and may represent a SIGNIFICANT performance hit for certain values of $min, $max, $lambda, and $maxIterations
    
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
    :   Samsara\Fermat\Types\NumberCollection

    description
    :   *No description available*
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."