# Mutability In PHP

Mutability is a property of instances of objects in object oriented languages. It refers to whether or not a method on the object changes the data referenced by that object's pointer (or in the case of PHP, its zval). If the method changes the data referenced by the object's zval, it is considered to be mutable. If the method does not change the data referenced, it is considered immutable.

In general, mutable objects have no memory of their previous states and evolve with the code as more operations are performed on them. Most objects that represent database results are mutable, such as those used in Doctrine or Elloquent. This reflects two properties of database results:

1. A change in the data should still point to the same database entry.
2. There should only ever be one set of data associated with a single database entry.

Thus, mutability is not a situation where all objects should be one way or the other, rather it depends on what the meaning of the data contained in the object is.

In PHP, mutability comes with the additional issue of scoping. For most purposes, objects can be treated as if they are passed by-reference at all times.

!!! see-also "See Also"
    The PHP Documentation contains examples on the specifics of how objects are passed between scopes. While it isn't exactly the same as passing by reference, it behaves in a very similar way in most situations.
    
    See the [php.net page](https://www.php.net/manual/en/language.oop5.references.php) for more information.
    
This is not an issue for some applications, mainly those where all operations are treated as atomic. However, this is not the case for many mathematical operations.

# Mutability In Fermat

Because of these factors, mutability in Fermat is generally left up to the developer using the library. Both mutable and immutable implementations are provided for most values, and the developer using Fermat is asked to choose which type they want at the time it is created.

The exceptions to this rule are usually noted within this documentation, and nearly always represent an underlying data-structure that should always be treated one way or the other.

In other cases, such as with implementations of the `Coordinate` abstract class, the reasons for making all the value classes one way or the other are related to the underyling math concepts they are meant to represent having some of the same properties as database results: that they should always point to the same dataset and that there should only ever be one representation of a given dataset.