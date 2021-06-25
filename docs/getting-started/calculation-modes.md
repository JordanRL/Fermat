# Available Modes

All modes which are defined exist as constants on the `Selectable` class. There are two modes currently available in Fermat.

## Scale Mode

###### Selectable::CALC_MODE_PRECISION = 1

In this mode, the best available string math implementation is used when a mathematical operation is performed. By default these are the functions provided by the BCMath library, however a future scope of this project is to provide integration with `ext-decimal`.

!!! note "For Certain Operations, BCMath Is Ignored"
    If the `ext-gmp` extension is present, it is used when both the input and output of an operation are guaranteed to be integers. This helps improve performance of operations which do not have a scale component. A non-exhaustive list of these situations includes:
    
    - Using the `factorial()` method.
    - Using the `add()` method on two integers.
    - Using the `multiply()` method on two integers.
    
    Note that `ext-gmp` is never used for divide, as optimistic use of the extension would result in a large performance cost for non-exact division at a very small performance gain in the case of exact division.
    
## Native Mode

###### Selectable::CALC_MODE_NATIVE = 2

In this mode, the native PHP math operators are used for calculation. The result is then converted to a string and stored according to the normal behavior of the class in question.

!!! note "Loss Of Scale"
    The scale defined in the object is ignored when this mode is used. This will result in values which behave as math operations in PHP would natively, including issues such as overflow and underflow.
    
!!! tip "Better Performance In Low Scale Situations"
    As a trade-off for accepting more ambiguous scale in the result, using this mode will decrease the computation required for basic math operations, in some cases quite significantly. If you are absolutely certain that your math will not result in an overflow or underflow, and your application is not sensitive to loss of scale in `float` values, using this mode will reduce the cost of each mathematical operation.
    
!!! tip "Expanding Native Types"
    With the mode set to native, this library functions as simply an extension to integer and float types that enables representations of imaginary numbers, complex numbers, matrices, coordinates, and statistics. In this way, the library may be useful even if arbitrary scale is not necessary for your application.
    
# Controlling the Mode of Objects

There are two main ways of controlling the mode used by your Fermat objects. The first is through the use of the default mode, and the second is with the use of the `setMode()` method.

## Default Mode

All objects that extend the `Number` abstract class set their current mode to the default calculation mode returned by `Numbers::getDefaultCalcMode()`. This check is only done during instantiation, meaning that changing the default calculation mode using `Numbers::setDefaultCalcMode()` will only affect objects instantiated after this change is made.

!!! potential-bugs "Interaction With Immutable Objects"
    Because immutable objects create new instances for every mathematical operation performed, changing the default calculation mode in the middle of application execution will result in all previously created immutables utilizing the original mode for their first mathematical operation, and the new mode for every subsequent operation.
    
    If immutable objects are being used, the default mode should never be changed in the middle of application execution. Instead, set it to the desired value at the beginning of the application, or use the `setMode()` method after a new object is instantiated.
    
!!! see-also "See Also"
    Further information on how to use default modes is available in the [Numbers Factory Class](using-factories.md#the-numbers-factory-class) documentation.
    
## Set Mode

###### setMode(int $mode)

This method is available on all classes that extend the `Number` abstract class. It sets the mode of the object it is called on to the mode provided. 

!!! caution "Use Only Defined Modes"
    While `setMode()` will accept any integer, you should only ever use inputs that are defined as constants on the `Selectable` class to avoid unexpected behaviors.
    
!!! potential-bugs "This Method Is Mutable For All Objects"
    Because of the nature of what this method does, it is mutable for all objects, including any implementations of immutable objects.