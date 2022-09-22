# Exceptions Used In This Library

The exceptions used in Fermat are all provided by the `samsara/common` package via composer. This is listed as a dependency in the composer.json. All these exceptions are in the namespace `Samsara\Exceptions`.

### Base Exceptions

- `Base\SystemError`: Extends `\Exception`. Used for errors that occur because of errors within the library.
- `Base\UsageError`: Extends `\Exception`. Used for errors that occur because of incorrect usage of the library.

### Thrown Exceptions

- `SystemError\LogicalError\IncompatibleObjectState`: This exception is thrown when the object is in a state that is incompatible with the requested operation. For example, calling `factorial()` on a [DecimalInterface](../roster/latest/Fermat Core/Types/Base/Interfaces/Numbers/DecimalInterface.md) instance that has a decimal value.
- `SystemError\PlatformError\MissingPackage`: This exception is thrown when an operation is performed that cannot be completed unless a missing [Fermat Module](modules.md) is installed.
- `UsageError\IntegrityConstraint`: This exception is thrown when a data integrity violation is found within a function. Most often this is due to poorly formatted or out of range arguments provided to a function.
- `UsageError\OptionalExit`: This exception is thrown when an error is encountered that may be solved by re-calling the same function with different arguments, for instance by providing a different scale setting.

# Handling Exceptions From This Library

Objects provided in this library are always in a valid state, and any exception thrown results in the object keeping its state from before the function call. Because of this, it may be possible in most situations to use a `try/catch` block to intelligently handle these exceptions based on the purpose of the numbers and data being provided to the Fermat objects.

!!! note "State Is Preserved Even For Mutable Objects"
    In the event that an exception is thrown, even mutable objects will retain their state from before the method call.