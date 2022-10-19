<?php

namespace Samsara\Fermat\Core\Bench\Providers;


use PhpBench\Attributes\Groups;
use Samsara\Fermat\Core\Provider\SequenceProvider;

class SequenceProviderBench
{

    #[Groups(['fibonacci', 'sequences', 'providers'])]
    public function benchFibonacciEasy()
    {
        SequenceProvider::nthFibonacciNumber(5);
    }

    #[Groups(['fibonacci', 'sequences', 'providers'])]
    public function benchFibonacciMedium()
    {
        SequenceProvider::nthFibonacciNumber(50);
    }

    #[Groups(['fibonacci', 'sequences', 'providers'])]
    public function benchFibonacciHard()
    {
        SequenceProvider::nthFibonacciNumber(500);
    }

}