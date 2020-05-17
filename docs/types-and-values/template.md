[Intro to the purpose of the type]

# Abstract Class: [class]

## Interfaces and Traits

The following interfaces and traits are available on classes which extend `[class]`

### Interfaces

###### Interface1

interface description

!!! exmaple "ImmutableDecimal Ex. 1"
    ```php
    <?php
    class ImmutableDecimal {
      protected function setValue($value, $precision = null, $base = 10)
      {
        /* omitted transformations and sanity checks */
        
        return new ImmutableDecimal($value, $precision, $base);
      }
    }
    ```

```math
f(x) = \int_{-\infty}^\infty
\hat f(\xi)\,e^{2 \pi i \xi x}
\,d\xi
```


###### Interface2

interface description

### Traits

###### Trait1

trait description

###### Trait2

trait description

## Abstract Methods

The following abstract methods must be implemented on classes which extend `[class]`

###### methodOne()

Description of method purpose. Satisfies `Interface1`.

# Available Value Objects

The following implementations of `[class]` are included with Fermat

## ImmutableClass

Description.

## MutableClass

Description