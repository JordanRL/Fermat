# Samsara\Fermat\Provider > RandomProvider

*No description available*


## Variables & Data


### Class Constants

!!! signature constant "RandomProvider::MODE_ENTROPY"
    value
    :   1

!!! signature constant "RandomProvider::MODE_SPEED"
    value
    :   2



## Methods


### Static Methods

!!! signature "public RandomProvider::randomInt(int|string|DecimalInterface $min, int|string|DecimalInterface $max, int $mode)"
    **$min**

    type
    :   int|string|DecimalInterface

    description
    :   *No description available*

    **$max**

    type
    :   int|string|DecimalInterface

    description
    :   *No description available*

    **$mode**

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

!!! signature "public RandomProvider::randomDecimal(int $scale, int $mode)"
    **$scale**

    type
    :   int

    description
    :   *No description available*

    **$mode**

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

!!! signature "public RandomProvider::randomReal(int|string|DecimalInterface $min, int|string|DecimalInterface $max, int $scale, int $mode)"
    **$min**

    type
    :   int|string|DecimalInterface

    description
    :   *No description available*

    **$max**

    type
    :   int|string|DecimalInterface

    description
    :   *No description available*

    **$scale**

    type
    :   int

    description
    :   *No description available*

    **$mode**

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




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."