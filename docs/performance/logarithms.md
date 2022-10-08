# Logarithms Performance

Below are the logarithmic operations available in Fermat and performance information about using them.

!!! signature "ln()"
    ##### ln
    **Native Mode**

    Ops/sec
    :   100,000

    EINOs
    :   350

    **Auto Mode**

    Ops/sec
    :   50,000

    EINOs
    :   700

    **Precision Mode**

    Ops/sec
    :   50,000

    EINOs
    :   700

    **Characteristics**

    Scale Sensitivity
    :   **High**

Note: This operation suffers enormously if ext-decimal is not available.

!!! signature "log10()"
    ##### log10
    **Native Mode**

    Ops/sec
    :   100,000

    EINOs
    :   320

    **Auto Mode**

    Ops/sec
    :   40,000

    EINOs
    :   800

    **Precision Mode**

    Ops/sec
    :   40,000

    EINOs
    :   800

    **Characteristics**

    Scale Sensitivity
    :   **High**

Note: This operation suffers enormously if ext-decimal is not available.

!!! signature "exp()"
    ##### exp
    **Native Mode**

    Ops/sec
    :   100,000

    EINOs
    :   350

    **Auto Mode**

    Ops/sec
    :   80,000

    EINOs
    :   435

    **Precision Mode**

    Ops/sec
    :   80,000

    EINOs
    :   435

    **Characteristics**

    Scale Sensitivity
    :   **High**

Note: This operation suffers enormously if ext-decimal is not available.