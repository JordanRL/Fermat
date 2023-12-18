# Samsara\Fermat\Core > Numbers

This class contains useful factory methods to create various numbers, verify the class of a given number, and generally handle all of the formatting necessary to satisfy the various constructors of valid value objects.


## Variables & Data


### Class Constants

!!! signature constant "Numbers::MUTABLE"
    ##### MUTABLE
    value
    :   'Samsara\Fermat\Core\Values\MutableDecimal'

!!! signature constant "Numbers::IMMUTABLE"
    ##### IMMUTABLE
    value
    :   'Samsara\Fermat\Core\Values\ImmutableDecimal'

!!! signature constant "Numbers::MUTABLE_FRACTION"
    ##### MUTABLE_FRACTION
    value
    :   'Samsara\Fermat\Core\Values\MutableFraction'

!!! signature constant "Numbers::IMMUTABLE_FRACTION"
    ##### IMMUTABLE_FRACTION
    value
    :   'Samsara\Fermat\Core\Values\ImmutableFraction'

!!! signature constant "Numbers::PI"
    ##### PI
    value
    :   '3.1415926535897932384626433832795028841971693993751058209749445923078164062862089986280348253421170679'

!!! signature constant "Numbers::TAU"
    ##### TAU
    value
    :   '6.2831853071795864769252867665590057683943387987502116419498891846156328125724179972560696506842341359'

!!! signature constant "Numbers::E"
    ##### E
    value
    :   '2.718281828459045235360287471352662497757247093699959574966967627724076630353547594571382178525166427'

!!! signature constant "Numbers::GOLDEN_RATIO"
    ##### GOLDEN_RATIO
    value
    :   '1.618033988749894848204586834365638117720309179805762862135448622705260462818902449707207204189391137'

!!! signature constant "Numbers::LN_10"
    ##### LN_10
    value
    :   '2.302585092994045684017991454684364207601101488628772976033327900967572609677352480235997205089598298'

!!! signature constant "Numbers::LN_2"
    ##### LN_2
    value
    :   '0.693147180559945309417232121458176568075500134360255254120680009493393621969694715605863326996418687'

!!! signature constant "Numbers::I_POW_I"
    ##### I_POW_I
    value
    :   '0.2078795763507619085469556198349787700338778416317696080751358830554198772854821397886002778654260353'

!!! signature constant "Numbers::E_M"
    ##### E_M
    value
    :   '0.5772156649015328606065120900824024310421593359399235988057672348848677267776646709369470632917467495'



## Methods


### Static Methods

!!! signature "public Numbers::make(mixed $type, mixed $value, int|null $scale, NumberBase $base)"
    ##### make
    **$type**

    type
    :   mixed

    description
    :   An instance of FQCN for any Fermat value class.

    **$value**

    type
    :   mixed

    description
    :   Any value which is valid for the constructor which will be called.

    **$scale**

    type
    :   int|null

    description
    :   The scale setting the created instance should have.

    **$base**

    type
    :   NumberBase

    description
    :   The base to create the number in. Note, this is not the same as the base of $value, which is always base-10
    
    

    **return**

    type
    :   ImmutableDecimal|MutableDecimal|ImmutableFraction|MutableFraction|Fraction|Decimal

    description
    :   *No description available*

    ###### make() Description:

    This class will make and return an instance of a concrete value.
    
     The main reason for this class is that you can pass an unknown value instance as the type parameter and it will behave as if you passed the FQCN.

---

!!! signature "public Numbers::make2Pi(int|null $scale)"
    ##### make2Pi
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

!!! signature "public Numbers::makeE(int|null $scale)"
    ##### makeE
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

!!! signature "public Numbers::makeEulerMascheroni(?int $scale)"
    ##### makeEulerMascheroni
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public Numbers::makeFractionFromString(string $type, string $value, NumberBase $base)"
    ##### makeFractionFromString
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
    :   NumberBase

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableFraction|Samsara\Fermat\Core\Values\MutableFraction|Samsara\Fermat\Core\Types\Fraction

    description
    :   *No description available*

---

!!! signature "public Numbers::makeFromBase10($type, $value, int|null $scale, NumberBase $base)"
    ##### makeFromBase10
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
    :   NumberBase

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Types\Decimal

    description
    :   *No description available*

---

!!! signature "public Numbers::makeGoldenRatio(int|null $scale)"
    ##### makeGoldenRatio
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

!!! signature "public Numbers::makeNaturalLog10(int|null $scale)"
    ##### makeNaturalLog10
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

!!! signature "public Numbers::makeNaturalLog2(int|null $scale)"
    ##### makeNaturalLog2
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

!!! signature "public Numbers::makeOne(int|null $scale)"
    ##### makeOne
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

!!! signature "public Numbers::makeOrDont(string|object $type, int|float|string|array|Decimal|Fraction $value, int|null $scale, NumberBase $base)"
    ##### makeOrDont
    **$type**

    type
    :   string|object

    description
    :   *No description available*

    **$value**

    type
    :   int|float|string|array|Decimal|Fraction

    description
    :   *No description available*

    **$scale**

    type
    :   int|null

    description
    :   *No description available*

    **$base**

    type
    :   NumberBase

    description
    :   
    
    

    **return**

    type
    :   ImmutableDecimal|MutableDecimal|Decimal|ImmutableDecimal[]|MutableDecimal[]|Decimal[]

    description
    :   *No description available*

---

!!! signature "public Numbers::makePi(int|null $scale)"
    ##### makePi
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

!!! signature "public Numbers::makeTau(null $scale)"
    ##### makeTau
    **$scale**

    type
    :   null

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public Numbers::makeZero(int|null $scale)"
    ##### makeZero
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




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."