# Samsara\Fermat\Values\Distribution > Normal

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Types\Distribution


## Methods


### Constructor

!!! signature "public Normal->__construct(int|float|DecimalInterface $mean, int|float|DecimalInterface $sd)"
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

    **Normal->__construct Description**

    Normal constructor.

---



### Static Methods

!!! signature "public Normal::makeFromMean(int|float|DecimalInterface $p, int|float|DecimalInterface $x, int|float|DecimalInterface $mean)"
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
    :   Samsara\Fermat\Values\Distribution\Normal

    description
    :   *No description available*

---

!!! signature "public Normal::makeFromSd(int|float|DecimalInterface $p, int|float|DecimalInterface $x, int|float|DecimalInterface $sd)"
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
    :   Samsara\Fermat\Values\Distribution\Normal

    description
    :   *No description available*

---



### Instanced Methods

!!! signature "public Normal->getSD()"
    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public Normal->getMean()"
    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public Normal->evaluateAt($x, int $scale)"
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

!!! signature "public Normal->cdf(int|float|DecimalInterface $x)"
    **$x**

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

!!! signature "public Normal->pdf(int|float|DecimalInterface $x1, null|int|float|DecimalInterface $x2)"
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
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public Normal->cdfProduct(FunctionInterface $function, $x)"
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
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public Normal->pdfProduct(FunctionInterface $function, $x1, $x2)"
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
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public Normal->percentBelowX(int|float|DecimalInterface $x)"
    **$x**

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

!!! signature "public Normal->percentAboveX(int|float|DecimalInterface $x)"
    **$x**

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

!!! signature "public Normal->zScoreOfX(int|float|DecimalInterface $x)"
    **$x**

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

!!! signature "public Normal->xFromZScore(int|float|DecimalInterface $z)"
    **$z**

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

!!! signature "public Normal->random()"
    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public Normal->rangeRandom(int|float|NumberInterface $min, int|float|NumberInterface $max, int $maxIterations)"
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