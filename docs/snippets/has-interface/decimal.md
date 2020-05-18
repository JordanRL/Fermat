!!! signature interface "DecimalInterface"
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers
    
    extends
    :   **SimpleNumberInterface** which extends **NumberInterface**
    
The `DecimalInterface` extends `SimpleNumberInterface` and adds the methods that are common to all decimal values. This includes trigonometric operations, integer operations such as `factorial()` or `isPrime()`, integer and float comparisons and conversions, rounding and truncating functions, and log functions.

While some of these can be done on fractions in pure mathematics, such as trigonometry functions, in practice computers are not well-equipped to handle the algorithms for them without actually performing the division implied by the fraction. Thus, to use these types of functions an explicit call to `asDecimal()` must first be made on classes that implement `Fraction`.

!!! see-also "See Also"
    The page for [Types & Values > Fractions](fractions.md) contains more information on the limitations of fraction representations within Fermat.