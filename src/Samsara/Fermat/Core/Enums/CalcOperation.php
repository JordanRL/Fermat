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

    // Arithmetic ops
    case Addition;
    case Subtraction;
    case Multiplication;
    case Division;
    case Power;
    case SquareRoot;

    // Utility ops
    case Modulo;
    case ContinuousModulo;
    case Compare;
    case Abs;

    // Trig ops
    case Sin;
    case Cos;
    case Tan;
    case Sec;
    case Csc;
    case Cot;

    // Hyperbolic trig ops
    case SinH;
    case CosH;
    case TanH;
    case SecH;
    case CscH;
    case CotH;

    // Inverse trig ops
    case ArcSin;
    case ArcCos;
    case ArcTan;
    case ArcSec;
    case ArcCsc;
    case ArcCot;

    // Logarithmic ops
    case Ln;
    case Exp;
    case Log10;

    // Integer ops
    case Factorial;
    case DoubleFactorial;
    case SubFactorial;

}
