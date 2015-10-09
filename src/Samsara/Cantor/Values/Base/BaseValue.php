<?php

namespace Samsara\Cantor\Values\Base;

abstract class BaseValue
{
    protected $value;

    protected $precision;

    protected $base;

    public function __construct($value, $precision = null, $base = 10)
    {
        if (!is_null($precision)) {
            $this->precision = $precision;
        }

        $this->base = $base;
        $this->value = (string)$value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getBase()
    {
        return $this->base;
    }

    public function getPrecision()
    {
        return $this->precision;
    }

    protected function getRadixPos()
    {
        return strpos($this->getValue(), '.');
    }

    protected function getFractionalPart()
    {
        $radix = $this->getRadixPos();
        if ($radix !== false) {
            return substr($this->getValue(), $radix);
        } else {
            return 0;
        }
    }

    protected function getWholePart()
    {
        $radix = $this->getRadixPos();
        if ($radix !== false) {
            return substr($this->getValue(), 0, ($radix-1));
        } else {
            return $this->getValue();
        }
    }

}