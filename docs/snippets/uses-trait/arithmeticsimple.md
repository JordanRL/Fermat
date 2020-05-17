Imports

- `ArithmeticSelectionTrait`
- `ArithmeticScaleTrait`
- `ArithmeticNativeTrait`

The `ArithmeticSimpleTrait` provides the implementations for all arithmetic functions that exist on values that implement the `SimpleNumberInterface`. The additional imported traits within this trait provide the various calculation modes that are used internally depending on the mode of object executing the method call.

!!! note "Accepts Complex Numbers as Arguments"
    While the `ArithmeticSimpleTrait` can accept implementations of `ComplexNumber` as arguments, it cannot be used in implementations of `ComplexNumber`.
    
!!! see-also "See Also"
    More detailed information on this trait is available on the [Arithmetic > Simple Numbers](../arithmetic/simple.md) page.