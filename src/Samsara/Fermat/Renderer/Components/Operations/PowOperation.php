<?php


namespace Samsara\Fermat\Renderer\Components\Operations;


use Samsara\Fermat\Renderer\Components\Interfaces\ComponentInterface;
use Samsara\Fermat\Types\Base\Interfaces\Numbers\NumberInterface;

class PowOperation implements ComponentInterface
{

    private string $left;
    private string $right;

    public function __construct(NumberInterface|ComponentInterface $left, NumberInterface|ComponentInterface $right)
    {

        $this->left = ($left instanceof NumberInterface ? $left->getValue() : $left->getOutput());
        $this->right = ($right instanceof NumberInterface ? $right->getValue() : $right->getOutput());

    }

    public function getOutput(): string
    {
        return '{'.$this->left.'}^{'.$this->right.'}';
    }
}