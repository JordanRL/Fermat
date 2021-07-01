# The Rounding Provider

The rounding provider allows you to round a number of arbitrary precision and scale using one of several rounding modes.

### Available Constants

The following constants are available on the `RoundProvider` class.

!!! signature constant "RoundingProvider::MODE_HALF_UP"
    type
    :   integer

    value
    :   1

!!! signature constant "RoundingProvider::MODE_HALF_DOWN"
    type
    :   integer

    value
    :   2

!!! signature constant "RoundingProvider::MODE_HALF_EVEN"
    type
    :   integer

    value
    :   3

!!! signature constant "RoundingProvider::MODE_HALF_ODD"
    type
    :   integer

    value
    :   4

!!! signature constant "RoundingProvider::MODE_HALF_ZERO"
    type
    :   integer

    value
    :   5

!!! signature constant "RoundingProvider::MODE_HALF_INF"
    type
    :   integer

    value
    :   6

!!! signature constant "RoundingProvider::MODE_CEIL"
    type
    :   integer

    value
    :   7

!!! signature constant "RoundingProvider::MODE_FLOOR"
    type
    :   integer

    value
    :   8

!!! signature constant "RoundingProvider::MODE_RANDOM"
    type
    :   integer

    value
    :   9

!!! signature constant "RoundingProvider::MODE_ALTERNATING"
    type
    :   integer

    value
    :   10

!!! signature constant "RoundingProvider::MODE_STOCHASTIC"
    type
    :   integer

    value
    :   11

### Available Public Static Methods

The following public static methods are available on the `RoundingProvider` class.

!!! signature "RoundingProvider::round(DecimalInterface $decimal, int $places = 0)"
    $decimal
    :   The number being rounded. It must be an instance of a class that implements `DecimalInterface`.

    $places
    :   The number of places after the decimal to begin rounding. For rounding to the nearest integer, provide zero for this parameter. To round to the nearest hundred you would provide -2.
    
    return
    :   The string of the rounded number

!!! signature "RoundingProvider::getRoundingMode()"
    return
    :   The integer corresponding to the current rounding mode. This can be compared to the class constants.

!!! signature "RoundingProvider::setRoundingMode(int $mode)"
    $mode
    :   The mode flag to use in future rounding calls. Intended to be used in conjunction with the class constants.

    return
    :   void