<?php

namespace Samsara\Fermat\Core\Enums;

/**
 * @codeCoverageIgnore
 * @package Samsara\Fermat\Core
 */
enum Currency: string
{

    case Dollar = '$';
    case Euro = '€';
    case Pound = '£';
    case YenYuan = '¥';
    case Rupee = '₹';
    case Won = '₩';
    case Rouble = '₽';
    case Dong = 'Đ';
    case Baht = '฿';
    case Lira = '₺';
    case Real = 'R$';

    case Bitcoin = '₿';
    case Ether = 'Ξ';

}
