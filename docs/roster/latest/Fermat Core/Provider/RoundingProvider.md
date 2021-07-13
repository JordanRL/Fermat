# Samsara\Fermat\Provider > RoundingProvider

*No description available*


## Variables & Data


### Class Constants

!!! signature constant "RoundingProvider::MODE_HALF_UP"
    ##### MODE_HALF_UP
    value
    :   1

!!! signature constant "RoundingProvider::MODE_HALF_DOWN"
    ##### MODE_HALF_DOWN
    value
    :   2

!!! signature constant "RoundingProvider::MODE_HALF_EVEN"
    ##### MODE_HALF_EVEN
    value
    :   3

!!! signature constant "RoundingProvider::MODE_HALF_ODD"
    ##### MODE_HALF_ODD
    value
    :   4

!!! signature constant "RoundingProvider::MODE_HALF_ZERO"
    ##### MODE_HALF_ZERO
    value
    :   5

!!! signature constant "RoundingProvider::MODE_HALF_INF"
    ##### MODE_HALF_INF
    value
    :   6

!!! signature constant "RoundingProvider::MODE_CEIL"
    ##### MODE_CEIL
    value
    :   7

!!! signature constant "RoundingProvider::MODE_FLOOR"
    ##### MODE_FLOOR
    value
    :   8

!!! signature constant "RoundingProvider::MODE_RANDOM"
    ##### MODE_RANDOM
    value
    :   9

!!! signature constant "RoundingProvider::MODE_ALTERNATING"
    ##### MODE_ALTERNATING
    value
    :   10

!!! signature constant "RoundingProvider::MODE_STOCHASTIC"
    ##### MODE_STOCHASTIC
    value
    :   11



## Methods


### Static Methods

!!! signature "public RoundingProvider::setRoundingMode(int $mode)"
    ##### setRoundingMode
    **$mode**

    type
    :   int

    description
    :   *No description available*

    **return**

    type
    :   void

    description
    :   *No description available*

---

!!! signature "public RoundingProvider::getRoundingMode()"
    ##### getRoundingMode
    **return**

    type
    :   int

    description
    :   *No description available*

---

!!! signature "public RoundingProvider::round(Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface $decimal, int $places)"
    ##### round
    **$decimal**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\DecimalInterface

    description
    :   *No description available*

    **$places**

    type
    :   int

    description
    :   *No description available*

    **return**

    type
    :   string

    description
    :   *No description available*

---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."