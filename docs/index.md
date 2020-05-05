# Goals of this Library

Fermat is a PHP library intended to accomplish the following goals:

1. Provide a consistent way to perform **arbitrary precision math** without making it easy to accidentally use PHP features (such as arithmetic operators or built in functions) that will reduce precision by casting to native `int` or `float` types.
2. Provide a library that enables the use of common complex math tasks, such as working with basic statistics functions, while still preserving arbitrary precision.
3. Provide a framework for working with non-integer and non-float math concepts such as fractions, coordinates, and shapes.

Things this library is NOT intended to do:

1. Be extremely performant. This library is concerned with always providing the program the desired answer wherever it is possible, regardless of how difficult that answer may be to calculate. Any calling code should keep in mind its tolerance for performance when using this library and use it accordingly. *This library will always try to give you the precision you ask for, even when that greatly slows down execution.*
2. Work with other libraries which offer math capabilities. Everything is self-contained within this library, and if you need to use another math library or a built-in math function to accomplish something, please create a GitHub issue here so that it can be added to the library and keep in mind that this library is not necessarily designed to guarantee compatibility.

That said, this library does offer ways for you to integrate. The state of all objects is available for reading at all times so you can put data into other libraries or functions, and the classes are all left open for extension. The references within the library are almost all to a base abstract class or interface, making it easier for a developer to extend a class with their own code.

Despite the fact that performance is not a primary goal of this library, it does use built in functions wherever possible (where doing so does not affect precision), and it will utilize the GMP functions, Stats functions, and PHP-DS types if those extensions are present in your installation of PHP. Installing these extensions should slightly increase performance in most use-cases.

# Limitations

This library implements certain constants (Pi, Tau, Euler's Number, and the Golden Ratio) as hardcoded constants out to 100 digits. Because many of the functions it performs (such as logarithms and trigonometry functions) depend on these constants, this library is not actually truly *arbitrary* precision. Instead, you can work with numbers that are accurate out to 100 decimal places, and you can calculate trigonometry functions out to 99 decimal places.

This library also only works with real numbers currently, and imaginary numbers, or any functions that might produce imaginary numbers, are not supported.