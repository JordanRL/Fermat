# Samsara\Fermat\Core\Provider > ConstantProvider

*No description available*


## Methods


### Static Methods

!!! signature "public ConstantProvider::makeE(int $digits)"
    ##### makeE
    **$digits**

    type
    :   int

    description
    :   
    
    

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

!!! signature "public ConstantProvider::makeGoldenRatio(int $digits)"
    ##### makeGoldenRatio
    **$digits**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   string

    description
    :   *No description available*

---

!!! signature "public ConstantProvider::makeIPowI(int $digits)"
    ##### makeIPowI
    **$digits**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   string

    description
    :   *No description available*

---

!!! signature "public ConstantProvider::makeLn10(int $digits)"
    ##### makeLn10
    **$digits**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   string

    description
    :   *No description available*

    ###### makeLn10() Description:

    The lnScale() implementation is very efficient, so this is probably our best bet for computing more digits of ln(10) to provide.

---

!!! signature "public ConstantProvider::makeLn1p1(int $digits)"
    ##### makeLn1p1
    **$digits**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   string

    description
    :   *No description available*

    ###### makeLn1p1() Description:

    This function is a special case of the ln() function where x can be represented by (n + 1)/n, where n is an integer. This particular special case converges extremely rapidly. For ln(1.1), n = 10.

---

!!! signature "public ConstantProvider::makeLn2(int $digits)"
    ##### makeLn2
    **$digits**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   string

    description
    :   *No description available*

    ###### makeLn2() Description:

    This function is a special case of the ln() function where x can be represented by (n + 1)/n, where n is an integer. This particular special case converges extremely rapidly. For ln(2), n = 1.

---

!!! signature "public ConstantProvider::makePi(int $digits)"
    ##### makePi
    **$digits**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   string

    description
    :   *No description available*

---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."