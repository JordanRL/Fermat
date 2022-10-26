<?php

namespace Samsara\Fermat\Coordinates\Values;

use PHPUnit\Framework\TestCase;

class SphericalCoordinateTest extends TestCase
{

    public function testAsCylindrical()
    {
        $a = new CartesianCoordinate('3', '4', '5');
        $spherical = $a->asSpherical();

        $this->assertEquals('5', $spherical->asCylindrical()->getAxis('r')->getValue());
        $this->assertEquals('0.927295218', $spherical->asCylindrical()->getAxis('theta')->getValue());
        $this->assertEquals('5', $spherical->asCylindrical()->getAxis('z')->getValue());
    }

    public function testAsSpherical()
    {
        $a = new CartesianCoordinate('3', '4', '5');
        $spherical = $a->asSpherical();

        $this->assertEquals('7.0710678119', $spherical->asSpherical()->getDistanceFromOrigin()->getValue());
        $this->assertEquals('0.7853981634', $spherical->asSpherical()->getPolarAngle()->getValue());
        $this->assertEquals('0.927295218', $spherical->asSpherical()->getPlanarAngle()->getValue());
    }

    public function testAsCartesian()
    {
        $a = new CartesianCoordinate('3', '4', '5');
        $spherical = $a->asSpherical();

        $this->assertEquals('3', $spherical->asCartesian()->getAxis('x')->getValue());
        $this->assertEquals('4', $spherical->asCartesian()->getAxis('y')->getValue());
        $this->assertEquals('5', $spherical->asCartesian()->getAxis('z')->getValue());
    }

    public function testGetDistanceFromOrigin()
    {
        $a = new CartesianCoordinate('3', '4', '5');
        $spherical = $a->asSpherical();

        $this->assertEquals('7.0710678119', $spherical->getDistanceFromOrigin()->getValue());
    }

    public function testDistanceTo()
    {
        $a = new CartesianCoordinate('3', '4', '5');
        $b = new CartesianCoordinate('0', '0', '0');
        $spherical = $a->asSpherical();

        $this->assertEquals('7.0710678119', $spherical->distanceTo($b)->getValue());
    }

    public function testGetPolarAngle()
    {
        $a = new CartesianCoordinate('3', '4', '5');
        $spherical = $a->asSpherical();

        $this->assertEquals('0.7853981634', $spherical->getPolarAngle()->getValue());
    }

    public function testGetPlanarAngle()
    {
        $a = new CartesianCoordinate('3', '4', '5');
        $spherical = $a->asSpherical();

        $this->assertEquals('0.927295218', $spherical->getPlanarAngle()->getValue());
    }
}
