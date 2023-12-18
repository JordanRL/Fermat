# Samsara\Fermat\Coordinates\Values > PolarCoordinate

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Coordinates\Types\Coordinate


### Implements

!!! signature interface "CoordinateInterface"
    ##### CoordinateInterface
    namespace
    :   Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates

    description
    :   

    *No description available*

!!! signature interface "TwoDCoordinateInterface"
    ##### TwoDCoordinateInterface
    namespace
    :   Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates

    description
    :   

    *No description available*



## Methods


### Constructor

!!! signature "public PolarCoordinate->__construct($rho, $theta)"
    ##### __construct
    **$rho**

    description
    :   *No description available*

    **$theta**

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*
    
---



### Instanced Methods

!!! signature "public PolarCoordinate->getDistanceFromOrigin()"
    ##### getDistanceFromOrigin
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public PolarCoordinate->getPolarAngle()"
    ##### getPolarAngle
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public PolarCoordinate->asCartesian(?int $scale)"
    ##### asCartesian
    **$scale**

    type
    :   ?int

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Coordinates\Values\CartesianCoordinate

    description
    :   *No description available*
    
---

!!! signature "public PolarCoordinate->asPolar()"
    ##### asPolar
    **return**

    type
    :   Samsara\Fermat\Coordinates\Values\PolarCoordinate

    description
    :   *No description available*
    
---

!!! signature "public PolarCoordinate->distanceTo(Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates\CoordinateInterface $coordinate)"
    ##### distanceTo
    **$coordinate**

    type
    :   Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates\CoordinateInterface

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---



### Inherited Methods

!!! signature "public Coordinate->getAxis($axis)"
    ##### getAxis
    **$axis**

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public Coordinate->axesValues()"
    ##### axesValues
    **return**

    type
    :   array

    description
    :   *No description available*
    
---

!!! signature "public Coordinate->numberOfDimensions()"
    ##### numberOfDimensions
    **return**

    type
    :   int

    description
    :   *No description available*
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."