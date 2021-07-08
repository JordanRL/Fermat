# Samsara\Fermat > Numbers

*No description available*


## Variables & Data


### Class Constants

!!! signature constant "Numbers::MUTABLE"
    value
    :   'Samsara\Fermat\Values\MutableDecimal'

!!! signature constant "Numbers::IMMUTABLE"
    value
    :   'Samsara\Fermat\Values\ImmutableDecimal'

!!! signature constant "Numbers::MUTABLE_FRACTION"
    value
    :   'Samsara\Fermat\Values\MutableFraction'

!!! signature constant "Numbers::IMMUTABLE_FRACTION"
    value
    :   'Samsara\Fermat\Values\ImmutableFraction'

!!! signature constant "Numbers::PI"
    value
    :   '3.1415926535897932384626433832795028841971693993751058209749445923078164062862089986280348253421170679'

!!! signature constant "Numbers::TAU"
    value
    :   '6.283185307179586476925286766559005768394338798750211641949889184615632812572417997256069650684234136'

!!! signature constant "Numbers::E"
    value
    :   '2.718281828459045235360287471352662497757247093699959574966967627724076630353547594571382178525166427'

!!! signature constant "Numbers::GOLDEN_RATIO"
    value
    :   '1.618033988749894848204586834365638117720309179805762862135448622705260462818902449707207204189391137'

!!! signature constant "Numbers::LN_10"
    value
    :   '2.302585092994045684017991454684364207601101488628772976033327900967572609677352480235997205089598298'

!!! signature constant "Numbers::I_POW_I"
    value
    :   '0.2078795763507619085469556198349787700338778416317696080751358830554198772854821397886002778654260353'



## Methods


### Static Methods

!!! signature "public Numbers::make($type, $value, int|null $scale, int $base)"
    **$type**

    description
    :   *No description available*

    **$value**

    description
    :   *No description available*

    **$scale**

    type
    :   int|null

    description
    :   *No description available*

    **$base**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   ImmutableDecimal|MutableDecimal|ImmutableFraction|MutableFraction|NumberInterface|FractionInterface

    description
    :   *No description available*

---

!!! signature "public Numbers::makeFromBase10($type, $value, int|null $scale, int $base)"
    **$type**

    description
    :   *No description available*

    **$value**

    description
    :   *No description available*

    **$scale**

    type
    :   int|null

    description
    :   *No description available*

    **$base**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface

    description
    :   *No description available*

---

!!! signature "public Numbers::makeOrDont(string|object $type, int|float|string|array|NumberInterface|DecimalInterface|FractionInterface $value, int|null $scale, int $base)"
    **$type**

    type
    :   string|object

    description
    :   *No description available*

    **$value**

    type
    :   int|float|string|array|NumberInterface|DecimalInterface|FractionInterface

    description
    :   *No description available*

    **$scale**

    type
    :   int|null

    description
    :   *No description available*

    **$base**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   ImmutableDecimal|MutableDecimal|NumberInterface|ImmutableDecimal[]|MutableDecimal[]|NumberInterface[]

    description
    :   *No description available*

---

!!! signature "public Numbers::makeFractionFromString(string $type, string $value, int $base)"
    **$type**

    type
    :   string

    description
    :   *No description available*

    **$value**

    type
    :   string

    description
    :   *No description available*

    **$base**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers\FractionInterface

    description
    :   *No description available*

---

!!! signature "public Numbers::makePi(int|null $scale)"
    **$scale**

    type
    :   int|null

    description
    :   
    
    

    **return**

    type
    :   DecimalInterface

    description
    :   *No description available*

---

!!! signature "public Numbers::makeTau(int|null $scale)"
    **$scale**

    type
    :   int|null

    description
    :   
    
    

    **return**

    type
    :   DecimalInterface

    description
    :   *No description available*

---

!!! signature "public Numbers::make2Pi(int|null $scale)"
    **$scale**

    type
    :   int|null

    description
    :   
    
    

    **return**

    type
    :   DecimalInterface

    description
    :   *No description available*

---

!!! signature "public Numbers::makeE(int|null $scale)"
    **$scale**

    type
    :   int|null

    description
    :   
    
    

    **return**

    type
    :   DecimalInterface

    description
    :   *No description available*

---

!!! signature "public Numbers::makeGoldenRatio(int|null $scale)"
    **$scale**

    type
    :   int|null

    description
    :   
    
    

    **return**

    type
    :   NumberInterface

    description
    :   *No description available*

---

!!! signature "public Numbers::makeNaturalLog10(int|null $scale)"
    **$scale**

    type
    :   int|null

    description
    :   
    
    

    **return**

    type
    :   NumberInterface

    description
    :   *No description available*

---

!!! signature "public Numbers::makeOne(int|null $scale)"
    **$scale**

    type
    :   int|null

    description
    :   
    
    

    **return**

    type
    :   ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public Numbers::makeZero(int|null $scale)"
    **$scale**

    type
    :   int|null

    description
    :   
    
    

    **return**

    type
    :   ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public Numbers::getDefaultCalcMode()"
    **return**

    type
    :   int

    description
    :   *No description available*

---

!!! signature "public Numbers::setDefaultCalcMode(int $mode)"
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




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."