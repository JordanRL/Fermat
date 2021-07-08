# Samsara\Fermat\Values\Geometry\CoordinateSystems > PolarCoordinate

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



## Methods


### Constructor

!!! signature "public PolarCoordinate->__construct($rho, $theta)"
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
    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public PolarCoordinate->distanceTo(Samsara\Fermat\Types\Base\Interfaces\Coordinates\CoordinateInterface $coordinate)"
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

!!! signature "public PolarCoordinate->asCartesian()"
    **return**

    type
    :   Samsara\Fermat\Values\Geometry\CoordinateSystems\CartesianCoordinate

    description
    :   *No description available*

---

!!! signature "public PolarCoordinate->getPolarAngle()"
    **return**

    type
    :   Samsara\Fermat\Values\ImmutableDecimal

    description
    :   *No description available*

---

!!! signature "public PolarCoordinate->asPolar()"
    **return**

    type
    :   Samsara\Fermat\Values\Geometry\CoordinateSystems\PolarCoordinate

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