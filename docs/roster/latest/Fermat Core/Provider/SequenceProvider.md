# Samsara\Fermat\Provider > SequenceProvider

*No description available*


## Variables & Data


### Class Constants

!!! signature constant "SequenceProvider::EULER_ZIGZAG"
    ##### EULER_ZIGZAG
    value
    :   array (0 => '1',1 => '1',2 => '1',3 => '2',4 => '5',5 => '16',6 => '61',7 => '272',8 => '1385',9 => '7936',10 => '50521',11 => '353792',12 => '2702765',13 => '22368256',14 => '199360981',15 => '1903757312',16 => '19391512145',17 => '209865342976',18 => '2404879675441',19 => '29088885112832',20 => '370371188237525',21 => '4951498053124096',22 => '69348874393137901',23 => '1015423886506852352',24 => '15514534163557086905',25 => '246921480190207983616',26 => '4087072509293123892361',27 => '70251601603943959887872',28 => '1252259641403629865468285',29 => '23119184187809597841473536',30 => '441543893249023104553682821',31 => '8713962757125169296170811392',32 => '177519391579539289436664789665',33 => '3729407703720529571097509625856',34 => '80723299235887898062168247453281',35 => '1798651693450888780071750349094912',36 => '41222060339517702122347079671259045',37 => '970982810785059112379399707952152576',38 => '23489580527043108252017828576198947741',39 => '583203324917310043943191641625494290432',40 => '14851150718114980017877156781405826684425',41 => '387635983772083031828014624002175135645696',42 => '10364622733519612119397957304745185976310201',43 => '283727921907431909304183316295787837183229952',44 => '7947579422597592703608040510088070619519273805',45 => '227681379129930886488600284336316164603920777216',46 => '6667537516685544977435028474773748197524107684661',47 => '199500252157859031027160499643195658166340757225472',48 => '6096278645568542158691685742876843153976539044435185',49 => '190169564657928428175235445073924928592047775873499136',50 => '6053285248188621896314383785111649088103498225146815121',)



## Methods


### Static Methods

!!! signature "public SequenceProvider::nthOddNumber(int $n, bool $asCollection, int $collectionSize)"
    ##### nthOddNumber
    **$n**

    type
    :   int

    description
    :   *No description available*

    **$asCollection**

    type
    :   bool

    description
    :   *No description available*

    **$collectionSize**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   DecimalInterface|NumberInterface|NumberCollection

    description
    :   *No description available*

    ###### nthOddNumber() Description:

    OEIS: A005408

---

!!! signature "public SequenceProvider::nthEvenNumber(int $n, bool $asCollection, int $collectionSize)"
    ##### nthEvenNumber
    **$n**

    type
    :   int

    description
    :   *No description available*

    **$asCollection**

    type
    :   bool

    description
    :   *No description available*

    **$collectionSize**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   DecimalInterface|NumberInterface|NumberCollection

    description
    :   *No description available*

    ###### nthEvenNumber() Description:

    OEIS: A005843

---

!!! signature "public SequenceProvider::nthPowerNegativeOne(int $n, bool $asCollection, int $collectionSize)"
    ##### nthPowerNegativeOne
    **$n**

    type
    :   int

    description
    :   *No description available*

    **$asCollection**

    type
    :   bool

    description
    :   *No description available*

    **$collectionSize**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   DecimalInterface|NumberInterface|NumberCollection

    description
    :   *No description available*

    ###### nthPowerNegativeOne() Description:

    OEIS: A033999

---

!!! signature "public SequenceProvider::nthEulerZigzag(int $n, bool $asCollection, int $collectionSize)"
    ##### nthEulerZigzag
    **$n**

    type
    :   int

    description
    :   *No description available*

    **$asCollection**

    type
    :   bool

    description
    :   *No description available*

    **$collectionSize**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   DecimalInterface|NumberInterface|NumberCollection

    description
    :   *No description available*

    ###### nthEulerZigzag() Description:

    OEIS: A000111

---

!!! signature "public SequenceProvider::nthBernoulliNumber($n)"
    ##### nthBernoulliNumber
    **$n**

    description
    :   
    
    

    **return**

    type
    :   DecimalInterface|NumberInterface

    description
    :   *No description available*

    ###### nthBernoulliNumber() Description:

    WARNING: This function is VERY unoptimized. Be careful of large m values.

---

!!! signature "public SequenceProvider::nthFibonacciNumber(int $n, bool $asCollection, int $collectionSize)"
    ##### nthFibonacciNumber
    **$n**

    type
    :   int

    description
    :   *No description available*

    **$asCollection**

    type
    :   bool

    description
    :   *No description available*

    **$collectionSize**

    type
    :   int

    description
    :   
    
    

    **return**

    type
    :   ImmutableDecimal|NumberCollection

    description
    :   *No description available*

    ###### nthFibonacciNumber() Description:

    OEIS: A000045
    
     This uses an implementation of the fast-doubling Karatsuba multiplication algorithm as described by 'Nayuki':
    
     https://www.nayuki.io/page/fast-fibonacci-algorithms

---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."