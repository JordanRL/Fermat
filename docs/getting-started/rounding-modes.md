# Rounding Modes

Rounding in Fermat is accomplished by making calls to the `RoundingProvider`. This provider accepts a `DecimalInterface` as its input, and provides the rounded value as a string. The rounding provider provides two broad types of rounding, deterministic and non-deterministic (or semi-deterministic).

!!! note "Rounding is Anything that Reduces Scale"
    Many people think of rounding as selecting the closest integer to a given number, with some kind of rule for what to do when you are half-way between two integers. However, rounding broadly covers any action that reduces the scale or precision of a number. Going from more digits to fewer digits is rounding regardless of how it is done.

    This means that even operations like `truncate()` or `floor()` or `ceil()` are rounding. Since truncate is better handled by the `Decimal` object itself, due to its knowledge of the internal state of the object, that is not handled by the `RoundingProvider`. All other kinds of rounding offered in Fermat utilize the `RoundingProvider` however.

    For this reason it is designed to be as lightweight as possible while still accomplishing its task.

The random provider has a private static property where it stores the mode to use while rounding. This property can be read and set using public static methods, but as it is a static property it affects all rounding operations after a mode is changed, even those you don't directly call. This is useful in most cases, since it allows you to set the rounding mode once at the beginning of your program and then utilize that rounding mode in every call that is made to the library.

The default mode is `RandomProvider::MODE_HALF_EVEN`. This is also the fallback mode if you ask for a non-existent rounding mode.

!!! caution "Rounding Mode Affects Many Operations Internally"
    Rounding occurs frequently in Fermat, since many operations produce more digits than the scale setting of your objects. The trigonometry functions, logarithmic function, and exponential functions all make a call to the `RoundingProvider` before returning a result. This means that selecting a rounding mode will affect the results you get from functions such as `tan()`, `sin()`, `exp()`, and `ln()`.

    In most cases this is not an issue, and would even be preferred to keep your results consistent with the other effects of rounding within the library. However, some modes such as Stochastic may produce results that are more inconsistent with the expectations of your program. 

    If you want to manually round an object once using a different mode, pass the mode as an argument to the `round()` method on your `Decimal` object instead of setting a new default more in the `RoundingProvider`. When done in this way, the provided mode will only be used for that one operation without affecting the default more for any other operations.

!!! see-also "See Also"
    The exact signatures associated with the `RandomProvider` can be found in the [Rounding Provider Reference Page](../reference/providers/rounding.md)

## Available Modes

!!! caution "Rounding Is Base-10 Referenced"
    As noted in other places, anything related to scale in this library is specific to base-10. While you can still round in other bases, the operations will be performed on the base-10 representation of the number instead of the base the `Decimal` object is in.

### Half Up

This rounding mode rounds the number towards positive infinity when halfway between two values.

!!! example "Examples"
    === "1.5"
        Using the "Half Up" mode:

        `1.5 -> 2`

    === "-1.5"
        Using the "Half Up" mode:

        `-1.5 -> -1`

    === "2.5"
        Using the "Half Up" mode:

        `2.5 -> 3`

    === "-2.5"
        Using the "Half Up" mode:

        `-2.5 -> -2`

### Half Down

This rounding mode rounds the number towards negative infinity when halfway between two values.

!!! example "Examples"
    === "1.5"
        Using the "Half Down" mode:
    
        `1.5 -> 1`

    === "-1.5"
        Using the "Half Down" mode:

        `-1.5 -> -2`

    === "2.5"
        Using the "Half Down" mode:

        `2.5 -> 2`

    === "-2.5"
        Using the "Half Down" mode:

        `-2.5 -> -3`

### Half Even

This rounding mode rounds the number towards the nearest even number when halfway between two values.

!!! example "Examples"
    === "1.5"
        Using the "Half Even" mode:

        `1.5 -> 2`

    === "-1.5"
        Using the "Half Even" mode:

        `-1.5 -> -2`

    === "2.5"
        Using the "Half Even" mode:

        `2.5 -> 2`

    === "-2.5"
        Using the "Half Even" mode:

        `-2.5 -> -2`

### Half Odd

This rounding mode rounds the number towards the nearest odd number when halfway between two values.

!!! example "Examples"
    === "1.5"
        Using the "Half Odd" mode:

        `1.5 -> 1`

    === "-1.5"
        Using the "Half Odd" mode:

        `-1.5 -> -1`

    === "2.5"
        Using the "Half Odd" mode:

        `2.5 -> 3`

    === "-2.5"
        Using the "Half Odd" mode:

        `-2.5 -> -3`

### Half Zero

This rounding mode rounds the number towards zero when halfway between two values.

!!! example "Examples"
    === "1.5"
        Using the "Half Zero" mode:

        `1.5 -> 1`

    === "-1.5"
        Using the "Half Zero" mode:

        `-1.5 -> -1`

    === "2.5"
        Using the "Half Zero" mode:

        `2.5 -> 2`

    === "-2.5"
        Using the "Half Zero" mode:

        `-2.5 -> -2`

### Half Infinity

This rounding mode rounds the number towards the nearest infinity (positive or negative) when halfway between two values.

!!! example "Examples"
    === "1.5"
        Using the "Half Infinity" mode:

        `1.5 -> 2`

    === "-1.5"
        Using the "Half Infinity" mode:

        `-1.5 -> -2`

    === "2.5"
        Using the "Half Infinity" mode:

        `2.5 -> 3`

    === "-2.5"
        Using the "Half Infinity" mode:

        `-2.5 -> -3`

### Ceil

This rounding mode rounds the number towards positive infinity, even for values which are not halfway between.

!!! example "Examples"
    === "1.5"
        Using the "Ceil" mode:

        `1.5 -> 2`

    === "-1.5"
        Using the "Ceil" mode:

        `-1.5 -> -1`

    === "2.2"
        Using the "Ceil" mode:

        `2.2 -> 3`

    === "-2.2"
        Using the "Ceil" mode:

        `-2.2 -> -2`

### Floor

This rounding mode rounds the number towards negative infinity, even for values which are not halfway between.

!!! example "Examples"
    === "1.5"
        Using the "Floor" mode:

        `1.5 -> 1`

    === "-1.5"
        Using the "Floor" mode:

        `-1.5 -> -2`

    === "2.2"
        Using the "Floor" mode:

        `2.2 -> 2`

    === "-2.2"
        Using the "Floor" mode:

        `-2.2 -> -3`

### Random

This rounding mode rounds the number in a direction that is randomly chosen when halfway between two values.

!!! example "Examples"
    === "1.5"
        Using the "Random" mode:

        `1.5 -> 1` 50% of the time
        `1.5 -> 2` 50% of the time

    === "1.7"
        Using the "Random" mode:

        `1.7 -> 2` 100% of the time

    === "2.2"
        Using the "Random" mode:

        `2.2 -> 2` 100% of the time

    === "-2.5"
        Using the "Random" mode:

        `-2.5 -> -2` 50% of the time
        `-2.5 -> -3` 50% of the time

### Alternating

This rounding mode rounds the number in a direction that alternates as more calls to `round()` are made when halfway between two values.

!!! example "Examples"
    === "1.5"
        Using the "Alternating" mode:

        `1.5 -> 2` on the first call
        `1.5 -> 1` on the second call

    === "1.7"
        Using the "Alternating" mode:

        `1.7 -> 2` 100% of the time

    === "2.2"
        Using the "Alternating" mode:

        `2.2 -> 2` 100% of the time

    === "-2.5"
        Using the "Alternating" mode:

        `-2.5 -> -3` on the first call
        `-2.5 -> -2` on the second call

### Stochastic

This rounding mode rounds the number in both directions in proportion to how close it is to both values. This occurs regardless of whether the number is halfway between. Please see the examples below for clarification.

!!! example "Examples"
    === "1.5"
        Using the "Stochastic" mode:

        `1.5 -> 2` 50% of the time
        `1.5 -> 1` 50% of the time

    === "1.7"
        Using the "Stochastic" mode:

        `1.7 -> 2` 70% of the time
        `1.7 -> 1` 30% of the time

    === "2.2"
        Using the "Stochastic" mode:

        `2.2 -> 3` 20% of the time
        `2.2 -> 2` 80% of the time

    === "-2.5"
        Using the "Stochastic" mode:

        `-2.5 -> -3` 50% of the time
        `-2.5 -> -2` 50% of the time