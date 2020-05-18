!!! signature interface "CoordinateInterface"
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Coordinates
    
    extends
    :   **TwoDCoordinateInterface**
    
This interface provides the base methods available to all coordinate systems. This includes the `asCartesian()` method, since any type of coordinate system in a Euclidean space can be represented by cartesian coordinates. Non-Euclidean spaces are unsupported in Fermat.