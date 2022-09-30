# Arithmetic Performance

Below are the arithmetic operations available in Fermat and performance information about using them.

!!! signature "add()"
    ##### add
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

Notes: None

!!! signature "subtract()"
    ##### subtract
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

Notes: None

!!! signature "multiply()"
    ##### multiply
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

Notes: None

!!! signature "divide()"
    ##### divide
    **Native Mode**

    Ops/sec
    :   150,000

    EINOs
    :   565

    **Auto Mode**

    Ops/sec
    :   125,000

    EINOs
    :   680

    **Precision Mode**

    Ops/sec
    :   150,000

    EINOs
    :   565

    **Characteristics**

    Scale Sensitivity
    :   **High**

Notes: Precision mode performance is around 25,000 Ops/sec at a scale setting of 50.

!!! signature "pow()"
    ##### pow
    **Native Mode**

    Ops/sec
    :   150,000

    EINOs
    :   565

    **Auto Mode**

    Ops/sec
    :   125,000

    EINOs
    :   680

    **Precision Mode**

    Ops/sec
    :   150,000

    EINOs
    :   565

    **Characteristics**

    Scale Sensitivity
    :   **Low** - **Extreme**

Note: Scale sensitivity is **low** if the exponent is an integer, however scale sensitivity is **extreme** if the exponent has a decimal component.

Precision mode performance is around 6,500 Ops/sec at a scale setting of 50 with a decimal exponent.

!!! signature "sqrt()"
    ##### sqrt
    **Native Mode**

    Ops/sec
    :   300,000

    EINOs
    :   150

    **Auto Mode**

    Ops/sec
    :   125,000

    EINOs
    :   360

    **Precision Mode**

    Ops/sec
    :   250,000

    EINOs
    :   180

    **Characteristics**

    Scale Sensitivity
    :   **Low**

Notes: 