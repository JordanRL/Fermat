## Mutability In PHP

Mutability is a property of instances of objects in object oriented languages. It refers to whether or not a method on the object changes the data referenced by that object's pointer (or in the case of PHP, its zval). If the method changes the data referenced by the object's zval, it is considered to be mutable. If the method does not change the data referenced, it is considered immutable.

In general, mutable objects have no memory of their previous states and evolve with the code as more operations are performed on them. Most objects that represent database results are mutable, such as those used in Doctrine or Eloquent. This reflects two properties of database results:

1. A change in the data should still point to the same database entry.
2. There should only ever be one set of data associated with a single database entry.

Thus, mutability is not a situation where all objects should be one way or the other, rather it depends on what the meaning of the data contained in the object is.

In PHP, mutability comes with the additional issue of scoping. For most purposes, objects can be treated as if they are passed by-reference at all times.

!!! see-also "See Also"
    The PHP Documentation contains examples on the specifics of how objects are passed between scopes. While it isn't exactly the same as passing by reference, it behaves in a very similar way in most situations.
    
    See the [php.net page](https://www.php.net/manual/en/language.oop5.references.php) for more information.
    
This is not an issue for some applications, mainly those where all operations are treated as atomic. However, this is not the case for many mathematical operations.

## Mutability In Fermat

Because of these factors, mutability in Fermat is generally left up to the developer using the library. Both mutable and immutable implementations are provided for most values, and the developer using Fermat is asked to choose which type they want at the time it is created.

!!! tip "Mutables Are For Niche Use-Cases In This Library"
    Although there are real use-cases for having a mutable numeric value, as detailed within this page, mutables generally make little sense for numeric types, such as `integer` or `float`. The values in this library should be treated more like numeric types that have fluent object interfaces than like normal PHP objects.
    
    For this reason, most developers in most situations should use the immutable types provided in this library. Immutables are returned automatically in all situations where the library generates or creates a number, such as from the `SequenceProvider` or from `Numbers::makePi()`.
    
    Unless you are absolutely certain that your use-case calls for a mutable numeric, it is generally safer to use the immutable values. Mutables have been provided to support specific behaviors seen for native `integer` and `float` types, namely reassignment operators like `+=` and `/=`.

The exceptions to this rule are usually noted within this documentation, and nearly always represent an underlying data-structure that should always be treated one way or the other.

In other cases, such as with implementations of the `Coordinate` abstract class, the reasons for making all the value classes one way or the other are related to the underlying math concepts they are meant to represent having some of the same properties as database results: that they should always point to the same dataset and that there should only ever be one representation of a given dataset.

## Choosing Between The Two

Which format you use is entirely up to you, but there are some situations that lend themselves more to one instead of the other.

First, lets look at the differences in your code that using one or the other might cause by taking the number 5 and adding 10.

!!! example "Example 1: Basic Usage Of Both"
    === "Immutable"
        ``` php
        <?php
        
        use Samsara\Fermat\Core\Values\ImmutableDecimal;
        
        $balance = new ImmutableDecimal(5);
        
        $newBalance = $balance->add(10);
        
        echo 'Current Balance: '.$newBalance->getValue();
        // Prints: 'Current Balance: 15'
        ```
    
    === "Mutable"
        ``` php
        <?php
        
        use Samsara\Fermat\Core\Values\MutableDecimal;
        
        $balance = new MutableDecimal(5);
        
        $balance->add(10);
        
        echo 'Current Balance: '.$balance->getValue();
        // Prints: 'Current Balance: 15'
        ```

### How To Create Equivalence Between the Two

In general, you can get the behavior of mutable objects with immutable objects by reassigning the new value to the same variable. This allows the state of the variable to evolve throughout the program, and results in the instance that was referenced in the previous line having its zval count reduced to zero. This allows garbage collection in PHP to periodically clean up all your orphaned instances.

So then, why ever use immutable objects? It seems like using immutables will use a much larger amount of memory while doing the same exact thing. Well, consider the next example.

!!! example "Example 2: Side Effects"
    === "Immutable"
        ``` php
        <?php
        
        use Samsara\Fermat\Core\Values\ImmutableDecimal;
        
        $oldBalance = new ImmutableDecimal(5);
        $deposit = new ImmutableDecimal(10);
        
        $newBalance = $oldBalance->add($deposit);
        
        echo 'Original Balance: '.$oldBalance.PHP_EOL;
        echo 'Deposit: '.$deposit.PHP_EOL;
        echo 'Current Balance: '.$newBalance;
        // Prints:
        // Original Balance: 5
        // Deposit: 10
        // Current Balance: 15
        ```
    
    === "Mutable"
        ``` php
        <?php
        
        use Samsara\Fermat\Core\Values\MutableDecimal;
        
        $oldBalance = new MutableDecimal(5);
        $deposit = new MutableDecimal(10);
        
        $newBalance = $oldBalance->add($deposit);
        
        echo 'Original Balance: '.$oldBalance.PHP_EOL;
        echo 'Deposit: '.$deposit.PHP_EOL;
        echo 'Current Balance: '.$newBalance;
        // Prints:
        // Original Balance: 15
        // Deposit: 10
        // Current Balance: 15
        ```

### Side Effects and Consistency

As we can see from the second set of examples, *assigning a value from a mutable function call can have side effects beyond the variable assignment*. In fact, preserving the value of the original object requires a lot of attention to detail in the calling code. To achieve the same result as the immutable example with the mutable example, we would need to make a whole new object just for the calculation, which would look something like this:

!!! example "Example 3: Mutables Used Without Side Effects"
    ``` php
    <?php 
    use Samsara\Fermat\Core\Values\MutableDecimal;
    
    $oldBalance = new MutableDecimal(5);
    $deposit = new MutableDecimal(10);
    
    $newBalance = new MutableDecimal($oldBalance->getValue());
    $newBalance->add($deposit);
    
    echo 'Original Balance: '.$oldBalance.PHP_EOL;
    echo 'Deposit: '.$deposit.PHP_EOL;
    echo 'Current Balance: '.$newBalance;
    // Prints:
    // Original Balance: 5
    // Deposit: 10
    // Current Balance: 15
    ```

Creating a new object on the fly to perform a calculation is in fact exactly how the implementation of immutables in Fermat is accomplished. Internally, any time a calculation has been performed, the new string with the resulting answer is stored in a newly created instance, instead of the instance that initiated the calculation.

This can be seen if you look at the different implementations of the `setValue()` abstract method in the [ImmutableDecimal](../roster/latest/Fermat Core/Values/ImmutableDecimal.md) and [MutableDecimal](../roster/latest/Fermat Core/Values/MutableDecimal.md) classes.

!!! example "Implementations Of setValue()"
    === "ImmutableDecimal"
        ``` php
        <?php
        class ImmutableDecimal {
          protected function setValue($value, $scale = null, $base = 10)
          {
            /* omitted transformations and sanity checks */
            
            return new ImmutableDecimal($value, $scale, $base);
          }
        }
        ```
    
    === "MutableDecimal"
        ``` php
        <?php
        class MutableDecimal {
          protected function setValue($value, $scale = null, $base = 10)
          {
            /* omitted transformations and sanity checks */
        
            $this->value = $this->translateValue($value);
        
            return $this;
          }
        }
        ```

The [ImmutableDecimal](../roster/latest/Fermat Core/Values/ImmutableDecimal.md) implementation returns a new instance, while the [MutableDecimal](../roster/latest/Fermat Core/Values/MutableDecimal.md) implementation sets the internal `$value` property directly and returns the current instance. This is the only meaningful difference between the two classes.

!!! note "setValue() As A Protected Method"
    For both mutable and immutable values, the `setValue()` method has a visibility of `protected`, preventing the calling scope from using it. This is intentional, as the values in these objects are meant to represent something closer to a memory address than a normal variable.
    
    Allowing `setValue()` to be called directly, even for mutable objects, could lead to some of the same problems that make memory address safety an issue for desktop applications. 
    
    Each instance can instead be seen as a [Finite-State Machine](https://en.wikipedia.org/wiki/Finite-state_machine) that evolves according to the state transitions defined on the object, i.e. the mathematical methods that are available on that class.
    
    This correctly reflects how math itself works, and helps prevent the developer from accidentally "breaking" math by inadvertantly inserting erroneous data in the middle of a series of calculations.

In this way, the immutable values act as time saving measures and sanity preserving measures in the case that you want to ensure side effects don't occur. Instead of manually creating new instances for every calculation and running the risk of forgetting on one line in a large program, you can simply request an instance of [ImmutableDecimal](../roster/latest/Fermat Core/Values/ImmutableDecimal.md) and it will do so automatically.

The downside to this is that the newly created object is not referenced anywhere except in the return value. Without a reference, the object becomes inaccessible if you do not assign the returned result to a variable in the calling scope.

### Guidelines On When To Use Each

Again, the exact usage of either is up to the developer, and it is *possible* to accomplish the same end result with either if the right design patterns are used. However, below is a brief rundown of when each is *generally* preferable.

#### Mutables May Be Preferred When

- The number represents a physical state or evolving state that can only move in one direction, such as with a hashing function
- The number represents a value which always depends on its previous value, such as recursively calculating a sum, or compiling changes to a total from a ledger of financial entries
- The number represents a value that has defined state-dependent behavior, such as in a [State Machine](https://en.wikipedia.org/wiki/Finite-state_machine)

#### Immutables May Be Preferred When

- The number represents data that may be used in multiple, unrelated contexts, such as using a User ID to calculate other values
- The number represents a concrete state, and the result of the equation will be a derived or dynamically generated value that doesn't actually exist within the data, such as using a population value to estimate average income
- The number represents a value that may be used multiple times for separate calculations without changing, such as the mean in a normal distribution

In general, for any given situation, one of these options will lead to cleaner, simpler, and easier to maintain code, while the other will lead to more fragile, harder to understand, and difficult to maintain code.

Which is which depends very much on the specific usage of that piece of data within your application.