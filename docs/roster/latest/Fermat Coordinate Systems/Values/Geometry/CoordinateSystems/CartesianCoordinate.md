# Samsara\Fermat\Values\Geometry\CoordinateSystems > CartesianCoordinate

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Types\Coordinate


### Implements

!!! signature interface "CoordinateInterface"
    ##### CoordinateInterface
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Coordinates

    description
    :   

    *No description available*

!!! signature interface "TwoDCoordinateInterface"
    ##### TwoDCoordinateInterface
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Coordinates

    description
    :   

    *No description available*

!!! signature interface "ThreeDCoordinateInterface"
    ##### ThreeDCoordinateInterface
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Coordinates

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
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public CartesianCoordinate->getDistanceFromOrigin()"
    ##### getDistanceFromOrigin
    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public CartesianCoordinate->distanceTo(CoordinateInterface $coordinate)"
    ##### distanceTo
    **$coordinate**

    type
    :   CoordinateInterface

    description
    :   
    
    

    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public CartesianCoordinate->asCartesian()"
    ##### asCartesian
    **return**

    type
    :   Samsara\Fermat\Values\Geometry\CoordinateSystems\CartesianCoordinate

    description
    :   *No description available*
    
---

!!! signature "public CartesianCoordinate->getPolarAngle()"
    ##### getPolarAngle
    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public CartesianCoordinate->getPlanarAngle()"
    ##### getPlanarAngle
    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public CartesianCoordinate->asSpherical()"
    ##### asSpherical
    **return**

    type
    :   Samsara\Fermat\Values\Geometry\CoordinateSystems\SphericalCoordinate

    description
    :   *No description available*
    
---

!!! signature "public CartesianCoordinate->asCylindrical()"
    ##### asCylindrical
    **return**

    type
    :   Samsara\Fermat\Values\Geometry\CoordinateSystems\CylindricalCoordinate

    description
    :   *No description available*
    
---

!!! signature "public CartesianCoordinate->asPolar()"
    ##### asPolar
    **return**

    type
    :   Samsara\Fermat\Values\Geometry\CoordinateSystems\PolarCoordinate

    description
    :   *No description available*
    
---



### Inherited Methods

!!! signature "public Coordinate->numberOfDimensions()"
    ##### numberOfDimensions
    **return**

    type
    :   int

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




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."