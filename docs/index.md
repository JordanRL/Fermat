# Goals of this Library

## Fermat is a PHP library intended to accomplish the following goals

### Consistent arbitrary precision math

Provide a consistent way to perform **arbitrary precision math** without making it easy to accidentally use PHP features (such as arithmetic operators or built in functions) that will reduce precision by casting to native `int` or `float` types.

### Complex math functions

Provide a library that enables the use of common complex math tasks, such as working with basic statistics functions, while still preserving arbitrary precision.

### Non-Integer & non-float number types

Provide a framework for working with non-integer and non-float math concepts such as fractions, coordinates, imaginary numbers, complex numbers, and shapes.

### Abstraction of math concepts

Provide a consistent abstraction for nearly any math concept that is likely to be relevant to a computer program, including many scientific programs.

## Things this library is NOT intended to do

### Be extremely performant. 

While Fermat has different modes that allow you to control performance to a degree, the abstraction and comprehensive nature of the library means that for certain uses, such as working with complex numbers, a large number of object instances may be created temporarily during a calculation, leading to significantly more overhead than using operands directly when doing basic math within common precision limitations.

Despite the fact that performance is not a primary goal of this library, it does use built in functions wherever possible (where doing so does not affect precision), and it will utilize the GMP functions, Stats functions, and PHP-DS types if those extensions are present in your installation of PHP. Installing these extensions should slightly increase performance in most use-cases.

### Work with other libraries which offer math capabilities. 

Everything is self-contained within this library, and if you need to use another math library or a built-in math function to accomplish something, please create a GitHub issue here so that it can be added to the library and keep in mind that this library is not necessarily designed to guarantee compatibility.

That said, this library does offer ways for you to integrate. The state of all objects is available for reading at all times so you can put data into other libraries or functions, and the classes are all left open for extension. The references within the library are almost all to a base abstract class or interface, making it easier for a developer to extend a class with their own code.

# Limitations 

This library implements certain constants (Pi, Tau, Euler's Number, and the Golden Ratio) as hardcoded constants out to 100 digits. Because many of the functions it performs (such as logarithms and trigonometry functions) depend on these constants, this library is not actually truly *arbitrary* precision. Instead, you can work with numbers that are accurate out to 100 decimal places, and you can calculate trigonometry functions out to 99 decimal places.

This library also only works with real numbers currently, and imaginary numbers, or any functions that might produce imaginary numbers, are not supported.