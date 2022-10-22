<?php

namespace Samsara\Fermat\Core\Enums;

/**
 * This is used internally in some places to differentiate between where (internally) a call was made from.
 *
 * @codeCoverageIgnore
 * @package Samsara\Fermat\Core
 */
enum CalcOperation
{

    case Addition;
    case Subtraction;
    case Multiplication;
    case Division;
    case Power;
    case SquareRoot;

    case Modulo;
    case ContinuousModulo;
    case Compare;

    case Sin;
    case Cos;
    case Tan;
    case Sec;
    case Csc;
    case Cot;

    case SinH;
    case CosH;
    case TanH;
    case SecH;
    case CscH;
    case CotH;

    case ArcSin;
    case ArcCos;
    case ArcTan;
    case ArcSec;
    case ArcCsc;
    case ArcCot;

}
