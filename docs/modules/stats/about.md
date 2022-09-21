# The Fermat Stats Module

This module provides various mathematical objects and functions to help a program perform complex statistical calculations, most often useful in scientific, data processing, and simulation based programs. Using Poisson Distributions, it is relatively easy to create an application that simulates sports matches. Using Normal Distributions, it becomes easy to generate data points that have properties weighted according to parameters that depend on the overall population of points.

While many programs don't see a benefit from utilizing statistical operations and functions, the ones that do benefit from it immensely.

## Key Concepts

This section details the key concepts of the library to understand how to create your program using it. This does *not* cover the key mathematical concepts within statistics that this program implements.

### Distributions

Within Fermat Stats, a Distribution is an object which extends the `Distribution` Type and implements the `DistributionInterface`.

While the parameters of a concrete distribution varies depending on what mathematical concept it represents, all distributions generally have a parameter that describes the 'most likely outcome', and a parameter that describes the 'likelihood of being a different outcome'. Generally, the likelihood decreases the further you are from the 'most likely outcome'. For most types of mathematical distributions, the 'most likely outcome' is related to the mean or average of the distribution, and the 'likelihood of being a different outcome' is related to the variance or standard deviation.

In Normal Distributions, the Mean and Standard Deviation *are* the two parameters, which are then combined with the Gauss Error Function and a variety of other mathematical functions to give us the properties of a normal distribution.

Poisson and Exponential distributions however are based on a different concept, time to recurrence. These are more useful in situations where you might be figuring out the chance of seeing a '100 year flood' within the next 10 years, for instance.

Because of their different constructions and different purposes, the Distributions within Fermat Stats share a less common interface than, for instance, the `Number` and `ComplexNumber` classes.

### StatsProvider

The `StatsProvider` contains several statistics static functions that allow the direct arbitrary precision calculation of some of the most complex formulas in statistics. Of note, there is an implementation within the `StatsProvider` of the Inverse Gauss Error Function, usually written as $`{erf}^{-1}(x)`$. This function in particular is horrendously complicated to formulate in a way that preserves arbitrary precision and is still conformal to the needs of a computer programming language. It may be the situation in some cases that this single function implementation is worth installing the entire library and module.