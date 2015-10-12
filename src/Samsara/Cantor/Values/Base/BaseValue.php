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

        if ($this->base != 10 && $this->getRadixPos() !== false) {
            throw new \Exception('This number has a fractional part. Currently, only whole numbers may be base converted. Floating point numbers must be in base 10.');
        }
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
            return substr($this->getValue(), $radix+1);
        } else {
            return 0;
        }
    }

    protected function getWholePart()
    {
        $radix = $this->getRadixPos();
        if ($radix !== false) {
            return substr($this->getValue(), 0, $radix);
        } else {
            return $this->getValue();
        }
    }

}