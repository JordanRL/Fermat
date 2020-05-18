This page details the arithmetic operations available for classes which implement `SimpleNumberInterface` and that use `ArithmeticSimpleTrait`.

# Available Modes

The `ArithmeticSimpleTrait` supports the modes:

- `Selectable::CALC_MODE_PRECISION`
- `Selectable::CALC_MODE_NATIVE`

!!! see-also "See Also"
    For more detail on calculation modes, see the [Getting Started > Calculation Modes](../getting-started/calculation-modes.md) page.
    
# Available Methods

The following methods are available for classes that implement `SimpleNumberInterface` and that use `ArithmeticSimpleTrait`.

!!! note "Return Value Depends On Context"
    While all return values will implement `NumberInterface`, the exact class returned depends on both what type is provided as the `$num`, and on the type of calling class. In general, the class that best fits the data of the result is returned, with preference given to the type of the calling class. See the examples below.
    
!!! warning "Mixed Argument Limitations"
    The arguments are listed in this documentation as `mixed`. In fact, the valid input types are:
    
    - An `integer`
    - A `float`
    - A `string` that contains only a single number in base-10
    - A `string` that contains only a single number in base-10 with the `i` character at the end, denoting an imaginary value
    - A `string` that contains two base-10 numbers separated by a division bar `/`
    - A `string` that contains two base-10 numbers separated by a `+` or `-`, with the number on the right having the `i` character after the value
    - An `object` that implements `NumberInterface`
    
    If the provided `$value` matches none of these, an exception of type `Samsara\Exceptions\UsageError\IntegrityConstraint` is thrown. 

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
        // Twenty amps in the circuit originally
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

This function subtracts `$num` from the current value and returns the result.

!!! example "Examples: Add"
    === "Decimal + Decimal"
        ```php
        <?php
        
        use Samsara\Fermat\Values\ImmutableDecimal;
        
        $balance = new ImmutableDecimal('100');
        $deposit = new ImmutableDecimal('50');
        
        $balance = $balance->subtract($deposit);
        
        echo "Balance: ".$balance;
        // Prints: 'Balance: 50'
        ```
    
    === "Decimal + Fraction"
        ```php
        <?php
        
        use Samsara\Fermat\Values\ImmutableDecimal;
        use Samsara\Fermat\Values\ImmutableFraction;
        
        $pizzas = new ImmutableDecimal('3');
        $eatenByFriends = new ImmutableFraction('3', '8');
        
        $pizzas = $pizzas->subtract($eatenByFriends);
        
        echo "I have ".$pizzas." pizzas";
        // Prints: 'I have 2.625 pizzas'
        ```
    
    === "Fraction + Decimal"
        ```php
        <?php
        
        use Samsara\Fermat\Values\ImmutableDecimal;
        use Samsara\Fermat\Values\ImmutableFraction;
        
        $leftOvers = new ImmutableFraction('25', '8');
        $eatenByFriends = new ImmutableDecimal('2');
        
        $pizzas = $leftOvers->subtract($eatenByFriends);
        
        echo "I have ".$pizzas->getNumerator()." slices (".$pizzas." pizzas)";
        // Prints: 'I have 9 slices (9/8 pizzas)'
        ```
    
    === "Decimal + Complex"
        ```php
        <?php
        
        use Samsara\Fermat\Values\ImmutableDecimal;
        use Samsara\Fermat\Values\ImmutableComplexNumber;
        
        // Four volts being removed from the circuit
        $newVoltage = new ImmutableDecimal('4');
        // Six volts in the circuit originally
        $oldVoltage = new ImmutableDecimal('6');
        // Twenty amps in the circuit originally
        $oldCurrent = new ImmutableDecimal('20i');
        $circuitState = new ImmutableComplexNumber($oldVoltage, $oldCurrent);
        
        $newCircuitState = $newVoltage->add($circuitState);
        
        echo 'Circuit State: '.$newCircuitState;
        // Prints: 'Circuit State: -2-20i'
        
        // Subtraction is not commutative
        $newCircuitState = $circuitState->add($newVoltage);
        
        echo 'Circuit State: '.$newCircuitState;
        // Prints: 'Circuit State: 2+20i'
        ```