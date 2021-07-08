# Samsara\Fermat\Types\Base\Interfaces\Coordinates > ThreeDCoordinateInterface

*No description available*


## Inheritance


## Methods


### Instanced Methods

!!! signature "public ThreeDCoordinateInterface->getPlanarAngle()"
    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public ThreeDCoordinateInterface->asSpherical()"
    **return**

    type
    :   Samsara\Fermat\Values\Geometry\CoordinateSystems\SphericalCoordinate

    description
    :   *No description available*

---

!!! signature "public ThreeDCoordinateInterface->asCylindrical()"
    **return**

    type
    :   Samsara\Fermat\Values\Geometry\CoordinateSystems\CylindricalCoordinate

    description
    :   *No description available*

---



### Inherited Methods

!!! signature "public CoordinateInterface->getAxis($axis)"
    **$axis**

    description
    :   *No description available*

    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public CoordinateInterface->axesValues()"
    **return**

    type
    :   array

    description
    :   *No description available*

---

!!! signature "public CoordinateInterface->getDistanceFromOrigin()"
    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public CoordinateInterface->numberOfDimensions()"
    **return**

    type
    :   int

    description
    :   *No description available*

---

!!! signature "public CoordinateInterface->distanceTo(Samsara\Fermat\Types\Base\Interfaces\Coordinates\CoordinateInterface $coordinate)"
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

!!! signature "public CoordinateInterface->asCartesian()"
    **return**

    type
    :   Samsara\Fermat\Values\Geometry\CoordinateSystems\CartesianCoordinate

    description
    :   *No description available*

---

!!! signature "public CoordinateInterface->getPolarAngle()"
    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---




---
!!! footer-link "This documentation was generated with [Roster](https://jordanrl.github.io/Roster/)."