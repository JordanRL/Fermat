# Samsara\Fermat\Coordinates\Values > CartesianCoordinate

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

!!! signature interface "ThreeDCoordinateInterface"
    ##### ThreeDCoordinateInterface
    namespace
    :   Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates

    description
    :   

    *No description available*



## Methods


### Constructor

!!! signature "public CartesianCoordinate->__construct($x, null $y, null $z)"
    ##### __construct
    **$x**

    description
    :   *No description available*

    **$y**

    type
    :   null

    description
    :   *No description available*

    **$z**

    type
    :   null

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*

    ###### __construct() Description:

    CartesianCoordinate constructor.
    
---



### Instanced Methods

!!! signature "public CartesianCoordinate->getAxis($axis)"
    ##### getAxis
    **$axis**

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public CartesianCoordinate->getDistanceFromOrigin(int|null $scale)"
    ##### getDistanceFromOrigin
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

!!! signature "public CartesianCoordinate->getPlanarAngle()"
    ##### getPlanarAngle
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public CartesianCoordinate->getPolarAngle()"
    ##### getPolarAngle
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public CartesianCoordinate->asCartesian()"
    ##### asCartesian
    **return**

    type
    :   Samsara\Fermat\Coordinates\Values\CartesianCoordinate

    description
    :   *No description available*
    
---

!!! signature "public CartesianCoordinate->asCylindrical()"
    ##### asCylindrical
    **return**

    type
    :   Samsara\Fermat\Coordinates\Values\CylindricalCoordinate

    description
    :   *No description available*
    
---

!!! signature "public CartesianCoordinate->asPolar(int|null $scale)"
    ##### asPolar
    **$scale**

    type
    :   int|null

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Coordinates\Values\PolarCoordinate

    description
    :   *No description available*
    
---

!!! signature "public CartesianCoordinate->asSpherical()"
    ##### asSpherical
    **return**

    type
    :   Samsara\Fermat\Coordinates\Values\SphericalCoordinate

    description
    :   *No description available*
    
---

!!! signature "public CartesianCoordinate->distanceTo(CoordinateInterface $coordinate, ?int $scale)"
    ##### distanceTo
    **$coordinate**

    type
    :   CoordinateInterface

    description
    :   
    
    

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



### Inherited Methods

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