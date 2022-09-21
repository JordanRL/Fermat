<?php

namespace Samsara\Fermat\Enums;

enum RoundingMode {
    // Tie-break modes
    case HalfUp;
    case HalfDown;
    case HalfEven;
    case HalfOdd;
    case HalfZero;
    case HalfInf;
    case HalfRandom;
    case HalfAlternating;

    // Always-round modes
    case Ceil;
    case Floor;
    case Stochastic;
}