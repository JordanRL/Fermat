# Random Modes

Random numbers can be obtained with Fermat by using the `RandomProvider`. This provider can be used to obtain random numbers of arbitrary size using either **Pseudo-Random Number Generators (PRNG)** or **Entropy Sources**.

PRNG will generate numbers that have the same distribution properties as true randomness, however the method by which they do this is deterministic and generally based upon a seed value.

Entropy sources utilize sources of randomness within the computer to extract bytes of entropy from the system as it continues to run. These sources vary by both hardware and OS, but in general terms, sources of entropy sometimes include:

- Variation in timings within the hardware (keyboard, mouse, IDE, etc.).
- TPM (Trusted Platform Module) chips if installed and enabled.
- RDRAND instruction from the CPU, seeding by hardware sources of entropy such as electrical variability, or cosmic ray originated bit-flip events in RAM.

Entropy within a computer system increases over time, and the degree to which the numbers provided from an entropy source corresponds to a **True Random Number Generator (TRNG)** depends on both the number of bits of entropy available within the system, and the number of random bits requested by the program running.

As the number of random bits requested by a program exceeds the bits of entropy within a system, the provided numbers begin to become more predictable through either statistical analysis or knowledge of initial conditions.

!!! note "Predictable Does Not Always Mean Practical"
    While PRNG and "deterministic randomness" in general have vectors that allow prediction, that does not mean it is always practical or possible within a given application for these number to actually be predicted or extracted.

In general, these are only a concern when a **Cryptographically Secure Random Number Generator (CSRNG)** is needed, and in practice a PHP application is unlikely to have "insufficient sources of entropy" as its largest security vulnerability.

PRNG is faster than TRNG, (in PHP the `rand()` function is approximately 15 times faster than `random_number()` or `random_bytes()`), so applications which can use PRNG generally benefit from doing so.

As rough guidelines with Fermat, you should use a PRNG when any of the following are true:

- You need numbers that form a random distribution, but it is not important if subsequent random numbers have a relationship with the previously generated random number.
- You need a random number in a math context, but not a computer security context.
- You need an **integer** that is **non-negative** and smaller than **PHP_INT_MAX**.

You should use TRNG when any of the following are true:

- You need a random number that is used as part of a security sensitive process, such as encryption.
- You need a **float**.
- You need an **integer** that can be **less than zero** or larger than **PHP_INT_MAX**.

The mode for PRNG is `RandomMode::Speed`, and the mode for TRNG is `RandomMode::Entropy`.

## Available Modes

!!! note "Mode Does Not Guarantee Method Used"
    While selecting `RandomMode::Entropy` will always guarantee that sources of entropy are used to generate your random numbers, it is possible that sources of entropy may be used even if you select `RandomMode::Speed`.

    This is because certain parameters for the range of a requested random number may require the use of alternative sources in order to contain your entire requested range.

### Speed

This mode uses `rand()` if it is possible given the arguments provided. If this PHP function cannot satisfy your request, your random number is generated using the Entropy process instead.

### Entropy

This mode generates random numbers using the `random_number()` function if possible given the arguments provided, then falls back to the `random_bytes()` function to create a sequence of bits sufficient to contain your entire range.

This mode is always used when the requested range requires outputs that `rand()` is unable to provide.

The `rand()` function is approximately one order of magnitude faster than `random_number()`, and `random_number()` is approximately one order of magnitude faster than the Fermat implementation using `random_bytes()`. 

!!! note "Potential Looping"
    This function collects a sequence of bits that contains your entire range. However, this necessarily means it also contains values outside of your requested range.

    When the generated value is outside of your requested range, the result is discarded and the entire process is tried again. This is because out-of-range values cannot be mapped to your requested range without changing the distribution of possible output values in a non-random way.

    This means that it is *technically* possible for the application to infinitely recurse, if all generated numbers for every recursive call are continually outside of your requested range.

    The highest possible chance for any range of a recursive call occurring is 50%, meaning that the chance of needing more than 10 calls is less than 0.1%.

    The average number of recursive calls to generate your random number for an arbitrary range would be in the vicinity of 1.5 extra calls.