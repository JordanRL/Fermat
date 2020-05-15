# Number Interfaces

These interfaces represent objects that contain individual numbers of some kind. `NumberInterface` is the base for this section, and all the other interfaces extend it.

!!! hint "Keep In Mind"
    Many places within this library code against the interfaces provided. Generally a value object should implement one of the composite interfaces, such as `DecimalInterface`, while a function which accepts an argument should require something like the `SimpleNumberInterface`.
    
    If you implement your own value classes, you need to implement the appropriate interface to use it with the rest of this library. A value function should never implement only `NumberInterface`.

## NumberInterface

###### abs(): SimpleNumberInterface

Applies the absolute value function to the current object and returns the object. Immutable objects will return a new instance that contains the absolute value, and mutable objects will apply the absolute value to the current instance.

###### absValue(): string

Applies the absolute value function to the current object and returns the result as a string. This will never alter the existing object.

###### add(int|float|numeric|NumberInterface $num): self

This adds the argument to the Value using the `ArithmeticProvider` or the native `+` operator depending on the calculation mode of the original object. For further information, see the section on [Arithmetic](arithmetic/).

### SimpleNumberInterface

#### DecimalInterface

#### FractionInterface

### ComplexNumberInterface