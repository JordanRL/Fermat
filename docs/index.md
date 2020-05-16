# Using This Documentation

A best effort is made to keep this documentation current, and the entire documentation is reviewed before every tagged release, however the documentation under v:latest may at times be out of date or incomplete.

### Info Boxes

Additional information is provided throughout this documentation using color coded information boxes. These are the styles used and what they are used for.

!!! note "Notes"
    This type of box is used to provide additional notes about a topic that may be rare use cases, or more detailed technical information that is not relevant to all readers of this documentation.
    
!!! tip "Tips"
    This type of box is used to give helpful tips on using the code described in the section where it appears. Sometimes this may be tips on architecture, while others it could be a tip on how to improve performance or help accomplish a common task.
    
!!! example "Examples"
    This type of box is used to provide examples of code, inputs, and outputs that illustrate a point which may be difficult to explain using just words. 
    
!!! see-also "See Also"
    This type of box is used to point you towards other parts of this documentation, or documentation elsewhere on the internet, which might provide more information on the section being described.
    
!!! potential-bugs "Potential Bugs"
    This type of box is used to provide a warning about common ways a programmer using this library might introduce a bug into their software through this library. It often details potentially unexpected type conversions, assumptions made that may not be obvious, or limitations that are inherent to this library or to PHP.
    
!!! caution "Cautions"
    This type of box is used to caution the developer about incorrect usage of the section being detailed. It is used when the incorrect usage will not result in exceptions on unexpected results, but instead might simply have undesirable side effects.
    
!!! warning "Warnings"
    This type of box is used to warn the developer about incorrect usage of the section being detailed. It is used when the incorrect usage will result in exceptions, but not unexpected results.
    
!!! danger "Danger"
    This type of box is used to alert the developer about potentially hard to find bugs that will result from an incorrect usage of the section being detailed. It is used when the incorrect usage will result in unexpected result without any exceptions or errors.

# What This Library Is For

### Consistent arbitrary precision math

Provides a consistent way to perform **arbitrary precision math** without making it easy to accidentally use PHP features (such as arithmetic operators or built in functions) that will reduce precision by casting to native `int` or `float` types.

### Complex math functions

Enables the use of common complex math tasks, such as working with basic statistics functions, while still preserving arbitrary precision.

### Non-Integer & non-float number types

Provides a framework for working with non-integer and non-float math concepts such as fractions, coordinates, imaginary numbers, complex numbers, and shapes.

### Abstraction of math concepts

Provides a consistent abstraction for nearly any math concept that is likely to be relevant to a computer program, including many scientific programs.

# What This Library Is NOT For

### Extreme Performance

While Fermat has different modes that allow you to control performance to a degree, the abstraction and comprehensive nature of the library means that for certain uses, such as working with complex numbers, a large number of object instances may be created temporarily during a calculation, leading to significantly more overhead than using operands directly when doing basic math within common precision limitations.

Despite the fact that performance is not a primary goal of this library, it does use built in functions wherever possible (where doing so does not affect precision), and it will utilize the GMP functions and PHP-DS types if those extensions are present in your installation of PHP. Installing these extensions should slightly increase performance in most use-cases.

!!! tip "Tip"
    A good way to increase performance is to avoid using imaginary and complex numbers if possible. The actual math involved in calculating simple operations involving these values is algorithmically complex, and leads to much longer execution times.
    
    Installing the suggested extensions will also help improve performance, in some situations quite significantly.
    
!!! caution "Other Extensions"
    Like many programs, this library's performance suffers enormously if `xDebug` is enabled. This can lead to execution times of more than one second for a single operation on complex numbers, making them almost totally unusable for the web.
    
    To avoid this, make sure that your production environment does not have `xDebug` enabled.

### Integration With Other Math Libraries

Everything is self-contained within this library, and if you need to use another math library or a built-in math function to accomplish something, please create a GitHub issue so that it can be added to the library. Keep in mind that this library is not necessarily designed to guarantee compatibility.

That said, this library does offer ways for you to integrate. The state of all objects is available for reading at all times enabling you to put data into other libraries or functions, and the classes are all left open for extension. The references within the library are almost all to a base abstract class or interface, making it easier for a developer to extend a class with their own code.

# Limitations 

Developers using this library should be aware of the following limitations which may lead to unexpected results.

### Extreme Precision

While this library can theoretically handle precisions on all operations up to 2^63 digits, in practice there are many operations in this library that have practical limits because of execution time. 

For instance, while the library would faithfully collect the first 10,000 digits of `sin(1)`, doing so may take prohibitively long, and depending on configuration and environment, the process may be killed before completion as a 'hung' process.

There are also several features in this library that by the nature of the math behind them can lead to infinite loops with the wrong inputs. While some basic measures exist within the library to detect and exit these situations with a thrown exception, doing so comprehensively is an example of the halting problem. This should not occur without direct calls to these areas, such as `SeriesProvider::maclaurenSeries()`.

!!! caution "Avoid Direct Usage"
    While the `SeriesProvider` methods are public, and can certainly be used directly, the internal workings of the functions are complicated to understand and simple to get wrong.
    
    In general, you should try to use consumers of the `SeriesProvider` first, such as the various distributions, or the `StatsProvider`.

For this reason, you should limit your requested precision to the smallest value which will still work for your intended application.

### Some Types of Math Require Assumptions

Some areas of math are ambiguously defined, depending on the exact axioms used. More generally, there are some types of calculations which give consistent behavior for a variety of axioms and mappings, or for which there is no consistent behavior defined within mathematics.

This is most obvious in the arc functions, such as `arctan()`. However, other areas make assumptions that may not be entirely clear at first.

!!! example "For Example"
    Calling `isEqual()` on a ComplexNumber will return false unless it is being compared to another `ComplexNumber` that has the same values for its real and imaginary part. More surprisingly perhaps, `ComplexNumber` objects do not have any of the `GreaterThan` or `LessThan` functions, as inequality comparison is poorly defined even between two complex numbers.

These peculiarities are documented as accurately as possible in this documentation where they occur.

### Immutables Are Used Internally

While this library provides both Mutable and Immutable versions of its base values, when a new object is generated internally it is nearly always an immutable version. This is to limit the side effects that might occur if object instance zvals that were used internally were changed in a parent scope. Because of this, methods which return a calculated value object always return the Immutable version of that value.

For this same reason, most of the time when an object is returned from an internal register, such as with the `getNumerator()` method on `Fraction`, any changes to that object will not be reflected in the instance of `Fraction` that it came from.

The exceptions to this are objects which contain a register of registers. An example would be the `Matrix` class, which internally has an array of `NumberCollection` objects. To prevent side effects in this situation, a clone is returned instead when the object is accessed with `getRow()` or `getColumn()`.

However, methods which act as array manipulation tools, such a `popRow()` and `shiftColumn()` will return the actual instance, and directly affect the internal data values.

This is related to PHP's internal structure of hashtables and zvals, and how these interact with the object model that PHP uses.

!!! see-also "See Also"
    The PHP Documentation contains examples on the specifics of how objects are passed between scopes. While it isn't exactly the same as passing by reference, it behaves in a very similar way in most situations.
    
    See the [php.net page](https://www.php.net/manual/en/language.oop5.references.php) for more information.

### This Library Can't Be Reliably Used With Math Operators

Because PHP doesn't allow operator overloading, using the native math operators on Fermat objects directly can very easily result in loss of precision, overflows and underflows, PHP fatal errors (f.e. when the object is in a non-base-10 format), and incorrect calculation (f.e. with complex and imaginary numbers).

!!! example "For Example"
    A `ComplexNumber` object that has the value `2+2i` added to the integer `4` with the `+` operator will issue a notice and give the result `6` instead of `6+2i`.