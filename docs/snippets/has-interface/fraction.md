!!! signature interface "FractionInterface"
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers
    
    extends
    :   **SimpleNumberInterface** which extends **NumberInterface**

The `FractionInterface` extends `SimpleNumberInterface` and adds the methods that are common to all fraction values. This includes `simplify()`, accessors for the numerator and denominator, and the `asDecimal()` method that returns the `Fraction` as an instance of `DecimalInterface`.

!!! note "ImmutableDecimals Are Returned From asDecimal()"
    While the interface only defines the `DecimalInterface` as a return value, the concrete classes returned by all included implementations are instances of `ImmutableDecimal`.

While some other functions can be done on fractions in pure mathematics, such as trigonometry functions, in practice computers are not well-equipped to handle the algorithms for them without actually performing the division implied by the fraction. Thus, to use these types of functions an explicit call to `asDecimal()` must first be made on classes that implement `Fraction`.

!!! see-also "See Also"
    The page for [Types & Values > Decimals](decimals.md) contains more information on the usage of `Decimal` values.