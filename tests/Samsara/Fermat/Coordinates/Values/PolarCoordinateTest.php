<?php

namespace Samsara\Fermat\Coordinates\Values;

use PHPUnit\Framework\TestCase;

class PolarCoordinateTest extends TestCase
{

    public function testAsPolar()
    {
        $coord = new CartesianCoordinate(
            '3',
            '4'
        );

        $polar = $coord->asPolar();

        $this->assertEquals('5', $polar->asPolar()->getAxis('rho')->truncateToScale(10)->getValue());
        $this->assertEquals('0.927295218', $polar->asPolar()->getAxis('theta')->truncateToScale(10)->getValue());
    }

    public function testDistanceTo()
    {
        $coord = new CartesianCoordinate(
            '3',
            '4'
        );

        $polar = $coord->asPolar();
        $this->assertEquals('5', $polar->distanceTo(new CartesianCoordinate('0', '0'))->getValue());
    }

    public function testGetDistanceFromOrigin()
    {
        $coord = new CartesianCoordinate(
            '3',
            '4'
        );

        $polar = $coord->asPolar();
        $this->assertEquals('5', $polar->getDistanceFromOrigin()->getValue());
    }

    public function testAsCartesian()
    {
        $coord = new CartesianCoordinate(
            '3',
            '4'
        );

        $polar = $coord->asPolar();
        $this->assertEquals('3', $polar->asCartesian()->getAxis('x')->getValue());
        $this->assertEquals('4', $polar->asCartesian()->getAxis('y')->getValue());
    }

    public function testGetPolarAngle()
    {
        $coord = new CartesianCoordinate(
            '3',
            '4'
        );

        $polar = $coord->asPolar();

        $this->assertEquals('0.927295218', $polar->getPolarAngle()->getValue());
    }
}
