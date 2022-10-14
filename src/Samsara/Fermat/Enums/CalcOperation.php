<?php

namespace Samsara\Fermat\Enums;

/**
 * This is used internally in some places to differentiate between where (internally) a call was made from.
 */
enum CalcOperation
{

    case Addition;
    case Subtraction;
    case Multiplication;
    case Division;
    case Power;
    case SquareRoot;

}
