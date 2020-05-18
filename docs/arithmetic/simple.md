This page details the arithmetic operations available for classes which implement `SimpleNumberInterface` and that use `ArithmeticSimpleTrait`.

# Available Modes

The `ArithmeticSimpleTrait` supports the modes:

- `Selectable::CALC_MODE_PRECISION`
- `Selectable::CALC_MODE_NATIVE`

!!! see-also "See Also"
    For more detail on calculation modes, see the [Getting Started > Calculation Modes](../getting-started/calculation-modes.md) page.
    
# Available Methods

The following methods are available for classes that implement `SimpleNumberInterface` and that use `ArithmeticSimpleTrait`.

###### add(mixed $num): NumberInterface

This function adds the current value with `$num` and returns the result.

!!! example "Examples: Add"
    === "Decimal + Decimal"
    ```php
    <?php
    
    use Samsara\Fermat\Values\ImmutableDecimal;
    
    $balance = new ImmutableDecimal('100');
    $deposit = new ImmutableDecimal('50');
    
    $balance = $balance->add($deposit);
    
    echo "Balance: ".$balance;
    // Prints: 'Balance: 150'
    ```
    
    === "Decimal + Fraction"
    ```php
    <?php
    
    use Samsara\Fermat\Values\ImmutableDecimal;
    use Samsara\Fermat\Values\ImmutableFraction;
    
    $pizzas = new ImmutableDecimal('3');
    $extraSlices = new ImmutableFraction('3', '8');
    
    $pizzas = $pizzas->add($extraSlices);
    
    echo "I have ".$pizzas." pizzas";
    // Prints: 'I have 3.375 pizzas'
    ```
    
    === "Fraction + Decimal"
    ```php
    <?php
    
    use Samsara\Fermat\Values\ImmutableDecimal;
    use Samsara\Fermat\Values\ImmutableFraction;
    
    $leftOvers = new ImmutableFraction('3', '8');
    $newOrder = new ImmutableDecimal('3');
    
    $pizzas = $leftOvers->add($newOrder);
    
    echo "I have ".$pizzas->getNumerator()." slices (".$pizzas." pizzas)";
    // Prints: 'I have 27 slices (27/8 pizzas)'
    ```
    
    === "Decimal + Complex"
    ```php
    <?php
    
    use Samsara\Fermat\Values\ImmutableDecimal;
    use Samsara\Fermat\Values\ImmutableComplexNumber;
    
    // Four volts being added to the circuit
    $newVoltage = new ImmutableDecimal('4');
    // Six volts in the circuit originally
    $oldVoltage = new ImmutableDecimal('6');
    // Twenty amps int he circuit originally
    $oldCurrent = new ImmutableDecimal('20i');
    $circuitState = new ImmutableComplexNumber($oldVoltage, $oldCurrent);
    
    $newCircuitState = $newVoltage->add($circuitState);
    
    echo 'Circuit State: '.$newCircuitState;
    // Prints: 'Circuit State: 10+20i'
    
    // Addition is commutative even for complex numbers
    $newCircuitState = $circuitState->add($newVoltage);
    
    echo 'Circuit State: '.$newCircuitState;
    // Prints: 'Circuit State: 10+20i'
    ```
    
###### subtract(mixed $num): NumberInterface