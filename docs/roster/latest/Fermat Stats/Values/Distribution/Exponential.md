# Samsara\Fermat\Values\Distribution > Exponential

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Types\Distribution


## Methods


### Constructor

!!! signature "public Exponential->__construct(int|float|DecimalInterface $lambda)"
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

    **Exponential->__construct Description**

    Exponential constructor.

---



### Instanced Methods

!!! signature "public Exponential->cdf(int|float|DecimalInterface $x, int $scale)"
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

!!! signature "public Exponential->pdf($x, int $scale)"
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
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public Exponential->rangePdf($x1, $x2, int $scale)"
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
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public Exponential->random()"
    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public Exponential->rangeRandom(int|float|DecimalInterface $min, int|float|DecimalInterface $max, int $maxIterations)"
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
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---



### Inherited Methods

!!! signature "public Distribution->randomSample(int $sampleSize)"
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