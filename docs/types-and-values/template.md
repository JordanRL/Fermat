test test test

!!! signature "pow(mixed $num): NumberInterface"
    return
    :   This is a description of the **return** value of the function. This has a very long definition, and will wrap many times, resulting and behavior from flexbox that will now be tested.
    
    $num
    :   This is a description of the **$num** argument
    
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