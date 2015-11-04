<?php

namespace Samsara\Fermat\Values\Base;

use Samsara\Fermat\Values\ImmutableNumber;

interface VectorInterface
{

    /**
     * @param int $axis
     * @return ImmutableNumber
     */
    public function getAxis($axis);

    /**
     * @return ImmutableNumber
     */
    public function getMagnitude();

    /* Vector Math */

    public function addVector(VectorInterface $vector);

    public function subtractVector(VectorInterface $vector);

    public function multiplyVector(VectorInterface $vector);

    public function divideVector(VectorInterface $vector);

    public function dotProduct(VectorInterface $vector);

    /* Scalar Math */

    public function addScalar(NumberInterface $number);

    public function subtractScalar(NumberInterface $number);

    public function multiplyScalar(NumberInterface $number);

    public function divideScalar(NumberInterface $number);

}