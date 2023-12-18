# Samsara\Fermat\Coordinates\Values > SphericalCoordinate

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

!!! signature interface "ThreeDCoordinateInterface"
    ##### ThreeDCoordinateInterface
    namespace
    :   Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates

    description
    :   

    *No description available*



## Methods


### Constructor

!!! signature "public SphericalCoordinate->__construct($rho, $theta, $phi)"
    ##### __construct
    **$rho**

    description
    :   *No description available*

    **$theta**

    description
    :   *No description available*

    **$phi**

    description
    :   *No description available*

    **return**

    type
    :   *mixed* (assumed)

    description
    :   *No description available*
    
---



### Instanced Methods

!!! signature "public SphericalCoordinate->getDistanceFromOrigin()"
    ##### getDistanceFromOrigin
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public SphericalCoordinate->getPlanarAngle()"
    ##### getPlanarAngle
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public SphericalCoordinate->getPolarAngle()"
    ##### getPolarAngle
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public SphericalCoordinate->asCartesian()"
    ##### asCartesian
    **return**

    type
    :   Samsara\Fermat\Coordinates\Values\CartesianCoordinate

    description
    :   *No description available*
    
---

!!! signature "public SphericalCoordinate->asCylindrical()"
    ##### asCylindrical
    **return**

    type
    :   Samsara\Fermat\Coordinates\Values\CylindricalCoordinate

    description
    :   *No description available*
    
---

!!! signature "public SphericalCoordinate->asSpherical()"
    ##### asSpherical
    **return**

    type
    :   Samsara\Fermat\Coordinates\Values\SphericalCoordinate

    description
    :   *No description available*
    
---

!!! signature "public SphericalCoordinate->distanceTo(Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates\CoordinateInterface $coordinate)"
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