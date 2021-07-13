# Samsara\Fermat\Provider > ConstantProvider

*No description available*


## Methods


### Static Methods

!!! signature "public ConstantProvider::makePi(int $digits)"
    ##### makePi
    **$digits**

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

!!! signature "public ConstantProvider::makeE(int $digits)"
    ##### makeE
    **$digits**

    type
    :   int

    description
    :   *No description available*

    **return**

    type
    :   string

    description
    :   *No description available*

    ###### makeE() Description:

    Consider also: sum [0 -> INF] { (2n + 2) / (2n + 1)! }
    
     This converges faster (though it's unclear if the calculation is actually faster), and can be represented by this set of Fermat calls:
    
     SequenceProvider::nthEvenNumber($n + 1)->divide(SequenceProvider::nthOddNumber($n)->factorial());
    
     Perhaps by substituting the nthOddNumber()->factorial() call with something tracked locally, the performance can be improved. Current performance is acceptable even out past 200 digits.

---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."