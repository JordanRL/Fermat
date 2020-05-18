test test test

!!! signature "pow(mixed $num): NumberInterface"
    $num
    :   This is a description of the **$num** argument
    
    return
    :   The return desc
    
!!! signature constant "ComplexNumbers::IMMUTABLE_COMPLEX"
    type
    :   string
    
    value
    :   The fully qualified class name of the **ImmutableComplexNumber** class.
    
!!! signature interface "DecimalInterface"
    namespace
    :   Samsara\Fermat\Types\Base\Interfaces\Numbers
    
    extends
    :   - **SimpleNumberInterface** which extends **NumberInterface**
    
!!! signature trait "ArithmeticSimpleTrait"
    namespace
    :   Samsara\Fermat\Types\Traits
    
    uses
    :   - **ArithmeticScaleTrait**
        - **ArithmeticNativeTrait**
        - **ArithmeticSelectionTrat**
    
    satisfies
    :   - **SimpleNumberInterface** (partially)