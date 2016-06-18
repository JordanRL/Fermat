<?php

namespace Samsara\Fermat\Values;

use Samsara\Fermat\Numbers;
use Samsara\Fermat\Provider\TrigonometryProvider;
use Samsara\Fermat\Types\Tuple;

class SphericalPosition
{

    /**
     * @var Tuple
     */
    private $position;

    public function __construct($lat, $lon)
    {
        $this->position = new Tuple($this->formatLatLon($lat), $this->formatLatLon($lon));
    }

    public function getLat()
    {
        return $this->position->get(0);
    }

    public function getLon()
    {
        return $this->position->get(1);
    }

    public function distanceTo(SphericalPosition $position)
    {

        $startLat = Numbers::make(Numbers::IMMUTABLE, TrigonometryProvider::degreesToRadians($this->getLat()));
        $endLat = Numbers::make(Numbers::IMMUTABLE, TrigonometryProvider::degreesToRadians($position->getLat()));

        $deltaLat = Numbers::make(Numbers::IMMUTABLE, TrigonometryProvider::degreesToRadians($endLat->subtract($startLat)));
        $deltaLon = Numbers::make(Numbers::IMMUTABLE, TrigonometryProvider::degreesToRadians($position->getLon()->subtract($this->getLon())));
        
        $a = $deltaLat->sin(1, 2)
            ->multiply($deltaLat->sin(1, 2))
            ->add(
                $startLat->cos()
                ->multiply($endLat->cos())
                ->multiply($deltaLon->sin(1, 2)->pow(2))
            );

    }

    private function formatLatLon($pos)
    {
        $pos = Numbers::makeOrDont(Numbers::IMMUTABLE, $pos);

        return $pos;
    }

}