# Samsara\Fermat\Values\Geometry\CoordinateSystems > SphericalCoordinate

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

!!! signature interface "ThreeDCoordinateInterface"
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Coordinates

    description
    :   *No description available*



## Methods


### Constructor

!!! signature "public SphericalCoordinate->__construct($rho, $theta, $phi)"
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
    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public SphericalCoordinate->distanceTo(Samsara\Fermat\Types\Base\Interfaces\Coordinates\CoordinateInterface $coordinate)"
    **$coordinate**

    type
    :   Samsara\Fermat\Types\Base\Interfaces\Coordinates\CoordinateInterface

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public SphericalCoordinate->getPolarAngle()"
    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public SphericalCoordinate->getPlanarAngle()"
    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public SphericalCoordinate->asCartesian()"
    **return**

    type
    :   Samsara\Fermat\Values\Geometry\CoordinateSystems\CartesianCoordinate

    description
    :   *No description available*

---

!!! signature "public SphericalCoordinate->asSpherical()"
    **return**

    type
    :   Samsara\Fermat\Values\Geometry\CoordinateSystems\SphericalCoordinate

    description
    :   *No description available*

---

!!! signature "public SphericalCoordinate->asCylindrical()"
    **return**

    type
    :   Samsara\Fermat\Values\Geometry\CoordinateSystems\CylindricalCoordinate

    description
    :   *No description available*

---



### Inherited Methods

!!! signature "public Coordinate->getAxis($axis)"
    **$axis**

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

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