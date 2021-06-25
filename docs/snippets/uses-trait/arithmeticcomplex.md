!!! signature trait "ArithmeticComplexTrait"
    namespace
    :   Samsara\Fermat\Types\Traits
    
    uses
    :   - **ArithmeticScaleTrait**
        - **ArithmeticNativeTrait**
        - **ArithmeticSelectionTrat**
    
    satisfies
    :   **ComplexNumberInterface** (partially)

The `ArithmeticComplexTrait` provides the implementations for all arithmetic functions that exist on values that implement the `ComplexNumberInterface`. The additional imported traits within this trait provide the various calculation modes that are used internally depending on the mode of object executing the method call.

!!! note "Accepts Simple Numbers as Arguments"
    While the `ArithmeticComplexTrait` can accept implementations of `SimpleNumberInterface` as arguments, it cannot be used in implementations of `SimpleNumberInterface`.
    
!!! see-also "See Also"
    More detailed information on this trait is available on the [Arithmetic > Complex Numbers](../arithmetic/complex.md) page.