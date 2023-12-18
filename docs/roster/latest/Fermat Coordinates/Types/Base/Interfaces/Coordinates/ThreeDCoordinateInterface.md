# Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates > ThreeDCoordinateInterface

*No description available*


## Inheritance


## Methods


### Instanced Methods

!!! signature "public ThreeDCoordinateInterface->asCylindrical()"
    ##### asCylindrical
    **return**

    type
    :   Samsara\Fermat\Coordinates\Values\CylindricalCoordinate

    description
    :   *No description available*
    
---

!!! signature "public ThreeDCoordinateInterface->asSpherical()"
    ##### asSpherical
    **return**

    type
    :   Samsara\Fermat\Coordinates\Values\SphericalCoordinate

    description
    :   *No description available*
    
---

!!! signature "public ThreeDCoordinateInterface->getPlanarAngle()"
    ##### getPlanarAngle
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---



### Inherited Methods

!!! signature "public CoordinateInterface->asCartesian()"
    ##### asCartesian
    **return**

    type
    :   Samsara\Fermat\Coordinates\Values\CartesianCoordinate

    description
    :   *No description available*
    
---

!!! signature "public CoordinateInterface->axesValues()"
    ##### axesValues
    **return**

    type
    :   array

    description
    :   *No description available*
    
---

!!! signature "public CoordinateInterface->distanceTo(Samsara\Fermat\Coordinates\Types\Base\Interfaces\Coordinates\CoordinateInterface $coordinate)"
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

!!! signature "public CoordinateInterface->getAxis($axis)"
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

!!! signature "public CoordinateInterface->getDistanceFromOrigin()"
    ##### getDistanceFromOrigin
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public CoordinateInterface->getPolarAngle()"
    ##### getPolarAngle
    **return**

    type
    :   Samsara\Fermat\Core\Values\ImmutableDecimal

    description
    :   *No description available*
    
---

!!! signature "public CoordinateInterface->numberOfDimensions()"
    ##### numberOfDimensions
    **return**

    type
    :   int

    description
    :   *No description available*
    
---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."