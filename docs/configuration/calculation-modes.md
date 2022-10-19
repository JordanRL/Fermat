# Calculation Modes

Calculation modes control how exactly a given calculation is performed. The following methods of calculation are used for mathematical operations, and the calculation mode allows you to control which methods are considered.

- The `BCMath` extension or `Decimal` extension, which operate on real numbers of arbitrary precision.
- The `GMP` extension, which operates on integers.
- The `PHP engine` math functions and operations, which work with both integers and floats, and provide a variable amount of precision up to about 12 decimal places.

The default calculation mode is `CalcMode::Auto`, which attempts to intelligently select the most computationally efficient mode given the precision needed.

## Available Modes

The mode for a given operation is determined from two sources. First, the calculation mode on the value object calling the method is checked. If the calculation mode on the value object is `null`, (which is the default value), then the global calculation mode currently set on the `CalculationModeProvider` is checked.

The default calculation mode is `CalcMode::Auto`, which will contextually use all calculation methods based on the runtime values of the objects in question.

!!! caution "Value-Specific Calculation Modes Persist"
    If a calculation mode is set for a value object using the `setMode()` method, all objects resulting from that calculation will inherit the same mode instead of have the default `null` value.

    In general this is prefferable and reduces unexpected bugs, however this means that once a value-specific calculation mode is set, all values derived from it will no longer be affected by the global calculation mode.

### Auto

The `CalcMode::Auto` mode will utilize all available calculation tools in an attempt to provide the most speed possible without compromising the requested scale.

Each mathematical operation may have its own criteria for deciding which calculation method to use, however the general logic of this mode is as follows:

- If all the inputs and outputs of a given calculation can be expected to be integers, the `GMP` functions are used.
- If `GMP` cannot be used, the inputs are then checked against the requested scale for the density of native float values. If it can be expected that native floats have enough density to provide the requested scale, then the native `PHP engine` math operations are used.
- If neither `GMP` nor the `PHP engine` can be used, then the fastest arbitrary precision operation available in your installation is used. For most PHP installations, this will be the `BCMath` extension. However, if it is installed then the `Decimal` extension is preferred as it is much, much faster.

### Precision Mode

In this mode, the best available string math implementation is used when a mathematical operation is performed. By default, these are the functions provided by the `BCMath` extension, however a future scope of this project is to provide integration with `ext-decimal`.
    
### Native Mode

In this mode, the native `PHP engine` math operators are used for calculation. The result is then converted to a string and stored according to the normal behavior of the class in question.

!!! note "Loss Of Scale"
    The scale defined in the object is ignored when this mode is used. This will result in values which behave as math operations in PHP would natively, including issues such as overflow and underflow.
    
!!! tip "Better Performance In Low Scale Situations"
    As a trade-off for accepting more ambiguous scale in the result, using this mode will decrease the computation required for basic math operations, in some cases quite significantly. If you are absolutely certain that your math will not result in an overflow or underflow, and your application is not sensitive to loss of scale in `float` values, using this mode will reduce the cost of each mathematical operation.
    
!!! tip "Expanding Native Types"
    With the mode set to native, this library functions as simply an extension to integer and float types that enables representations of imaginary numbers, complex numbers, matrices, coordinates, and statistics. In this way, the library may be useful even if arbitrary scale is not necessary for your application.
    
## Controlling the Mode of Objects

There are two main ways of controlling the mode used by your Fermat objects. The first is through the use of the default mode, and the second is with the use of the `setMode()` method.

### Default Mode

The mode currently returned by `CalculationModeProvider` is used by default for all classes that extend the `Number` abstract class. This mode at application start is `CalcMode::Auto`, but can be changed at any time using `CalculationModeProvider::setCurrentMode()`.
    
### Set Mode

The `setMode()` method is available on all classes that extend the `Number` abstract class. It sets the mode of the object it is called on to the mode provided. 

This setting will `null` by default, allowing the object to use the default mode described above. If this is used to set a specific mode on an object, the default mode will be ignored, and the specific mode set will cascade to any objects that object creates through calculations.

!!! caution "Use Only Defined Modes"
    While `setMode()` will accept any integer, you should only ever use inputs that are defined on the `CalcMode` enum to avoid unexpected behaviors.
    
!!! potential-bugs "This Method Is Mutable For All Objects"
    Because of the nature of what this method does, it is mutable for all objects, including any implementations of immutable objects.