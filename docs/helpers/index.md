# Helpers for `NumberInterface`

The following helper methods are available on any class that implements `NumberInterface`

### getValue(): string

Returns the current value as a string. **Note:** This will return the current value in whatever the current base is.

### getBase(): int

Returns the base of the current value.

### isNegative(): bool

Returns `true` if the number is less than 0, `false` otherwise. **Note:** Zero is not considered negative.

### isPositive(): bool

Returns `true` if the number is greater than 0, `false` otherwise. **Note:** Zero is not considered positive.

### isEqual(int|float|numeric|NumberInterface $value): bool

Returns `true` if the object and value are equal to each other, `false` otherwise. **Note:** Does not round or truncate based on precision prior to comparison.

### isGreaterThan(int|float|numeric|NumberInterface $value): bool

Returns `true` if the object is greater than the value argument, `false` otherwise. **Note:** Does not round or truncate based on precision prior to comparison.

### isGreaterThanOrEqualTo(int|float|numeric|NumberInterface $value): bool

Returns `true` if the object is greater than or equal to the value argument, `false` otherwise. **Note:** Does not round or truncate based on precision prior to comparison.

### isLessThan(int|float|numeric|NumberInterface $value): bool

Returns `true` if the object is less than the value argument, `false` otherwise. **Note:** Does not round or truncate based on precision prior to comparison.

### isLessThanOrEqualTo(int|float|numeric|NumberInterface $value): bool

Returns `true` if the object is less than or equal to the value argument, `false` otherwise. **Note:** Does not round or truncate based on precision prior to comparison.

### compare(int|float|numeric|NumberInterface $value): bool

Returns `1` if the object is greater than the value argument, `0` if they are equal, and `-1` if the object is less than the value argument. **Note:** Does not round or truncate based on precision prior to comparison.

### abs(): self

Applies the absolute value function to the current object. **Note:** For Mutable objects, this method **will** alter the object.

### absValue(): string

Returns the absolute value of the current object as a string without altering the object.

### convertToBase(int $base): self

Changes the base of the current object and converts its value to that base.

# Helpers for `DecimalInterface`

The following helper methods are available on any class that implements `DecimalInterface`

### getPrecision(): int

Returns the precision setting of the current object.

### isInt(): bool, isNatural(): bool, isWhole(): bool

These methods are all aliases for the same question: does this number have numbers (other than zero) after the decimal place. It returns `false` if there is, and `true` if there is not.

### isPrime(): bool

Returns `true` if the current object is a prime number, `false` if it is not. Also returns `false` if the number is not a whole number. **Note:** For very large primes, or composites with only very large prime factors, this method may be very slow.

### round(int $decimals = 0): self

Rounds the current object to the specified number of decimal places.

### truncate(int $decimals = 0): self

Truncates the current object to the specified number of decimal places.

### roundToPrecision(int $precision): self

Rounds the current object to the specified precision and sets the precision of the current object to that value.

### truncateToPrecision(int $precision): self

Truncates the current object to the specified precision and sets the precision of the current object to that value.

### ceil(): self

Rounds the current object up to the nearest whole number.

### floor(): self

Rounds the current object down to the nearest whole number.

### modulo(int|numeric $mod): self

Gives the remainder of the current object divided by the modulus, but the modulus must be a whole number.

### continuousModulo(int|float|numeric|DecimalInterface $mod): self

Gives the remainder of the current object divided by the modulus to 100 digits, and the modulus can be a decimal number.

### getLeastCommonMultiple(int|numeric|DecimalInterface $num): self

Returns the Least Common Multiple of the current object and the argument. The argument must be a whole number. **Note:** For Mutable objects, this method **will** modify the object.

### getGreatestCommonDivisor(int|numeric|DecimalInterface $num): self

Returns the Greatest Common Divisor of the current object and the argument. The argument must be a whole number. **Note:** This method will **always** return an `ImmutableNumber` instance, and will never modify the current object.

### numberOfLeadingZeros(): int

This returns the number of zeros between the decimal point and the first non-zero number after the decimal place.

### convertForModification(): int|bool

This converts the current object to base10 are returns the original base as an int. If the base was already 10, it returns false. This is useful in conjunction with `convertFromModification()` to perform math of some kind of a number in a different base, since all math libraries for working with numbers expect the numbers to be in base10.

### convertFromModification(int|bool $oldBase): self

This converts the current object to the base supplied by the argument, or exits early if `false` is supplied. **This is the only method that will modify an `ImmutableNumber` object in place without returning a new instance.** This is useful in conjunction with `convertForModification()` to perform math of some kind of a number in a different base, since all math libraries for working with numbers expect the numbers to be in base10.