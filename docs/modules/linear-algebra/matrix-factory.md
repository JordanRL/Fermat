# The Matrices Factory Class

The `Samsara\Fermat\Core\Matrices` factory class provides access to several pre-built matrices that may be useful in common situations.

### Available Constants

The following constants are available on the `Matrices` class.

!!! signature constant "Matrices::IMMUTABLE_MATRIX"
type
:   string

    value
    :   The fully qualified class name of the **ImmutableMatrix** class.

!!! signature constant "Matrices::MUTABLE_MATRIX"
type
:   string

    value
    :   The fully qualified class name of the **MutableMatrix** class.

### Available Factory Methods

The following factory methods are available on the `Matrices` class.

!!! signature "Matrices::zeroMatrix(string $type, int $rows, int $columns)"
$type
:   The type (mutable or immutable) of **Matrix** to create

    $rows
    :   The number of rows the generated matrix should have
    
    $columns
    :   The number of columns the generated matrix should have
    
    return
    :   An instance of the specified matrix type with the given dimensions where all values in the matrix are the number zero

!!! example "For Example"
A zero matrix of two rows and three columns would look like:

    ```
    [0 0 0]  
    [0 0 0]
    ```

!!! signature "Matrices::onesMatrix(string $type, int $rows, int $columns)"
$type
:   The type (mutable or immutable) of **Matrix** to create

    $rows
    :   The number of rows the generated matrix should have
    
    $columns
    :   The number of columns the generated matrix should have
    
    return
    :   An instance of the specified matrix type with the given dimensions where all values in the matrix are the number one

!!! example "For Example"
A ones matrix of two rows and three columns would look like:

    ```
    [1 1 1]  
    [1 1 1]
    ```

!!! signature "Matrices::identityMatrix(string $type, int $size)"
$type
:   The type (mutable or immutable) of **Matrix** to create

    $size
    :   The number of rows and columns the generated matrix should have
    
    return
    :   A square matrix where the dimensions match the integer given in **$size**. This matrix is an identity matrix, which is often used in matrix math, where the diagonal consists of ones, and all other values are zero

!!! example "For Example"
An identity matrix of size three would look like:

    ```
    [1 0 0]  
    [0 1 0]  
    [0 0 1]
    ```

!!! signature "Matrices::cofactorMatrix(string $type, int $size)"
$type
:   The type (mutable or immutable) of **Matrix** to create

    $size
    :   The number of rows and columns the generated matrix should have
    
    return
    :   A square matrix where the dimensions match the integer given in **$size**. The matrix is filled with alternating values of 1 and -1 in a checkerboard pattern, starting with positive 1 in position [0, 0].

When multiplied by another matrix, this will swap the sign of every other value in the matrix.

!!! example "For Example"
A cofactor matrix of size three would look like:

    ```
    [+ - +]  
    [- + -]  
    [+ - +]
    ```

