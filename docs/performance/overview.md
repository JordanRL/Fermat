# Performance

The performance of Fermat depends very sensitively on the extensions installed, the settings you have configured for your objects, and the values which you are calculating for.

The Fermat library is benchmarked in many scenarios, and the details of performance for the different kinds of math supported are detailed within this section.

## Installed Extensions

The Fermat library **requires** the [GMP](https://www.php.net/manual/en/book.gmp) and [BCMath](https://www.php.net/manual/en/book.bc.php) extensions to be available, and both are listed as requirements in the `composer.json` file for the library.

If the [Decimal](http://php-decimal.io/#introduction) extension is installed, it is used in place of the BCMath extension, and offers a 5-100x performance improvement over BCMath within Fermat, depending on the operation and algorithm.

If the [Ds](https://www.php.net/manual/en/book.ds.php) extension is installed, then it is used in place of arrays for the [NumberCollection](../roster/latest/Fermat%20Core/Types/NumberCollection.md) class as well as for the [Coordinate Systems Module](../modules/coordinate-systems/about.md). This provides substantial memory and read/write performance in most circumstances.

## Auto Mode Performance

If you look through the documented performance metrics, you'll see that it's fairly rare for the `CalcMode::Auto` setting to be the best performing mode for any given operation. So then why is it the default mode?

Simply put, the Auto mode is more consistent in its performance across different inputs and settings. It looks at your requested scale, the values that you are using, the algorithms that can be used, and the extensions that are available, then chooses what is anticipated to be the best performing based on all of these factors.

These checks ensure that it will almost always be slightly worse than going directly to the best option, but in different conditions or different points in your program, the best option may be different.

Auto mode should have the best overall performance across a wide variety of applications, data, and settings.

## Scale Sensitivity

The performance metrics in this documentation are for the default scale setting of 10. All calculations are negatively impacted by using a larger value for scale, however different operations have different sensitivity to scale.

As such, the "Scale Sensitivity" factor in the documentation provides some idea of how the performance might be impacted by using larger scales.

A "Scale Sensitivity" of **Low** means that the performance drops off in a somewhat linear fashion, usually at a rate of ~10% for every additional 10 digits of precision.

A "Scale Sensistivity" of **Moderate** means that the performance drops off in a somewhat linear fashion, but at a higher rate than Low.

A "Scale Sensistivity" of **High** means that the performance drops off rapidly, often in a quadratic fashion.

A "Scale Sensitivity" of **Extreme** means that the performance cannot be expected to remain within near the given performance, even if scale is increased by only a factor of 2.

!!! note "Scale Sensitivity Applies to Precision Mode"
    The **Native** [Calculation Mode](../configuration/calculation-modes.md) does not suffer from any scale sensitivity, since your requested scale is ignored when this mode is used. However, that also means that the result given does not necessarily have the scale you requested.

    The **Auto** [Calculation Mode](../configuration/calculation-modes.md) often, but not always, has lower sensitivity to scale.

## Performance Metrics

Within this section, performance metrics are provided in two forms, and shown for each of the calculation modes. For example:

!!! signature "add()"
    **Native Mode**

    Ops/sec
    :   150,000

    EINOs
    :   565

    **Auto Mode**

    Ops/sec
    :   155,000

    EINOs
    :   550

    **Precision Mode**

    Ops/sec
    :   150,000

    EINOs
    :   565

    **Characteristics**

    Scale Sensitivity
    :   **Low**

### Ops/sec

These figures are the best performance you can expect with all extensions installed, and represent the number of times you could call that calculation per second within a loop for common value ranges.

The actual number of operations per second that you can realize in your circumstances depend on your system configuration and hardware. These benchmarks were performed using a Ryzen 3900.

This value does not include any overhead for creating additional objects, changing values each loop, or anything of that nature. They represent the pure calculation speed of the algorithms used and the operations performed during the calculation itself.

### Equivalent Inline Native Operations (EINOs)

This represents how many native PHP operations you would need in your code to take the same amount of time as the operation does within Fermat.

!!! example "For Example"
    === "Fermat"
        ```php
        <?php

        $value = $fermatValue->add(5);
        ```

    === "EINO"
        ```php
        <?php

        $value = 3 + 5;
        ```

Another way of putting this is if you removed Fermat from your application and performed all the math yourself directly in your code using the functions and operators available in PHP core, you would be able to perform that calculation X times without slowing down your application.

It is important to note that **this comparison is only valid for a scale setting of 10 and for small numbers (less than 10,000 for most operations)**.

Outside of those bounds, the EINOs would begin to give rounding and mathematical errors that may impact the correctness of your program.

!!! example "For Example"
    ```php
    <?php

    // This gets converted to a float in PHP
    $num1 = PHP_INT_MAX + 1;

    // The float does not have enough precision to change here
    $num2 = $num1 + 1;

    // This statement is evaluated as true
    if ($num1 == $num2) {
        echo "Uh oh.";
    }

    // Prints: Uh oh.
    ```