# The ComplexNumbers Factory Class

The `Samsara\Fermat\Core\ComplexNumbers` factory class allows you to create instances of the Value classes which implement the `ComplexNumberInterface`.

### Available Constants

The following constants are available on the `ComplexNumbers` class.

!!! signature constant "ComplexNumbers::IMMUTABLE_COMPLEX"
type
:   string

    value
    :   The fully qualified class name of the **ImmutableComplexNumber** class.

!!! signature constant "ComplexNumbers::MUTABLE_COMPLEX"
type
:   string

    value
    :   The fully qualified class name of the **MutableComplexNumber** class.

### Available Factory Methods

The following factory methods are available on the `ComplexNumbers` class.

!!! signature "ComplexNumbers::make(string $type, string|array|NumberCollectionInterface $value)"
$type
:   The type (mutable or immutable) of **ComplexNumber** to create

    $value
    :   The initial value of the created instance; see notes below
    
    return
    :   The instance created from the given inputs; the instance will extend **ComplexNumber** and implement the **ComplexNumberInterface**

If the value is a `string`, it is assumed to be in the format: `REAL+IMAGINARY` or `REAL-IMAGINARY`

!!! note "Note"
In string format, the input for this factory method **MAY** have a minus sign in front of the real part, but **MUST** have either a plus or minus sign in front of the imaginary part.

    The sign is applied to the number that follows and is stored in the `ImmutableDecimal` for that number.

You may also provide either an `array` or a `NumberCollection` that have exactly two values which implement the `SimpleNumberInterface`.

!!! warning "Warning"
The real part must have a key of `0`, and the imaginary part must have a key of `1` in the given `array` or `NumberCollection`.
