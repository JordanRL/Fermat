# Samsara\Fermat\Values\Geometry\CoordinateSystems > CartesianCoordinate

*No description available*


## Inheritance


### Extends

- Samsara\Fermat\Types\Coordinate


### Implements

!!! signature interface "CoordinateInterface"
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Coordinates

    description
    :   *No description available*

!!! signature interface "TwoDCoordinateInterface"
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Coordinates

    description
    :   *No description available*

!!! signature interface "ThreeDCoordinateInterface"
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Coordinates

    description
    :   *No description available*



## Methods


### Constructor

!!! signature "public CartesianCoordinate->__construct($x, null $y, null $z)"
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

    **CartesianCoordinate->__construct Description**

    CartesianCoordinate constructor.

---



### Instanced Methods

!!! signature "public CartesianCoordinate->getAxis($axis)"
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
    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public CartesianCoordinate->distanceTo(CoordinateInterface $coordinate)"
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
    **return**

    type
    :   Samsara\Fermat\Values\Geometry\CoordinateSystems\CartesianCoordinate

    description
    :   *No description available*

---

!!! signature "public CartesianCoordinate->getPolarAngle()"
    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public CartesianCoordinate->getPlanarAngle()"
    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public CartesianCoordinate->asSpherical()"
    **return**

    type
    :   Samsara\Fermat\Values\Geometry\CoordinateSystems\SphericalCoordinate

    description
    :   *No description available*

---

!!! signature "public CartesianCoordinate->asCylindrical()"
    **return**

    type
    :   Samsara\Fermat\Values\Geometry\CoordinateSystems\CylindricalCoordinate

    description
    :   *No description available*

---

!!! signature "public CartesianCoordinate->asPolar()"
    **return**

    type
    :   Samsara\Fermat\Values\Geometry\CoordinateSystems\PolarCoordinate

    description
    :   *No description available*

---



### Inherited Methods

!!! signature "public Coordinate->numberOfDimensions()"
    **return**

    type
    :   int

    description
    :   *No description available*

---

!!! signature "public Coordinate->axesValues()"
    **return**

    type
    :   array

    description
    :   *No description available*

---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."