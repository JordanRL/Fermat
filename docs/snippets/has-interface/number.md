!!! signature interface "NumberInterface"
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers
    
    extends
    :   None

`NumberInterface` contains the base arithmetic methods that are a component of all numbers in mathematics. This includes the basics of addition, subtraction, multiplication, and division, as well as pow and square root.

It also provides the `isEqual()` method, to enable equality comparison, as well as `getScale()`. Some classes which implement the `NumberInterface` don't actually accept scale as an argument, but instead contain objects that do. `Fraction` is an example of such a class, as both its numerator and denominator are instances of `ImmutableDecimal`.

In addition, the `is` and `as` methods for `Real`, `Imaginary`, and `Complex` are provided by this interface.