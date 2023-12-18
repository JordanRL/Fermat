# Samsara\Fermat\Stats\Provider > StatsProvider

*No description available*


## Methods


### Static Methods

!!! signature "public StatsProvider::beta(int|float|string|Decimal $a, int|float|string|Decimal $b)"
    ##### beta
    **$a**

    type
    :   int|float|string|Decimal

    description
    :   *No description available*

    **$b**

    type
    :   int|float|string|Decimal

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

    ###### beta() Description:

    The beta function:
    
     B(a, b) = (a - 1)! (b - 1)!
    
     a + b - 1)!

---

!!! signature "public StatsProvider::binomialCoefficient($n, $k)"
    ##### binomialCoefficient
    **$n**

    description
    :   *No description available*

    **$k**

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public StatsProvider::complementNormalCDF($x)"
    ##### complementNormalCDF
    **$x**

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public StatsProvider::gaussErrorFunction($x, int|null $scale)"
    ##### gaussErrorFunction
    **$x**

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

!!! signature "public StatsProvider::incompleteBeta(Samsara\Fermat\Core\Types\Decimal|string|int|float $x, Samsara\Fermat\Core\Types\Decimal|string|int|float $a, Samsara\Fermat\Core\Types\Decimal|string|int|float $b)"
    ##### incompleteBeta
    **$x**

    type
    :   Samsara\Fermat\Core\Types\Decimal|string|int|float

    description
    :   *No description available*

    **$a**

    type
    :   Samsara\Fermat\Core\Types\Decimal|string|int|float

    description
    :   *No description available*

    **$b**

    type
    :   Samsara\Fermat\Core\Types\Decimal|string|int|float

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public StatsProvider::inverseErrorCoefficients(int $termIndex)"
    ##### inverseErrorCoefficients
    **$termIndex**

    type
    :   int

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableFraction

    description
    :   *No description available*

---

!!! signature "public StatsProvider::inverseGaussErrorFunction($z, int|null $scale)"
    ##### inverseGaussErrorFunction
    **$z**

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

!!! signature "public StatsProvider::inverseNormalCDF(float|int|string|Decimal $p, int|null $scale)"
    ##### inverseNormalCDF
    **$p**

    type
    :   float|int|string|Decimal

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

!!! signature "public StatsProvider::normalCDF($x)"
    ##### normalCDF
    **$x**

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