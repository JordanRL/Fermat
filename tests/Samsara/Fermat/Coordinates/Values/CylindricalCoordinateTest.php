<?php

namespace Samsara\Fermat\Coordinates\Values;

use PHPUnit\Framework\TestCase;

class CylindricalCoordinateTest extends TestCase
{

    public function testGetPolarAngle()
    {
        $a = new CartesianCoordinate('3', '4', '5');
        $cylindrical = $a->asCylindrical();

        $this->assertEquals('0', $cylindrical->getPolarAngle()->getValue());
    }

    public function testAsCylindrical()
    {
        $a = new CartesianCoordinate('3', '4', '5');
        $cylindrical = $a->asCylindrical();

        $this->assertEquals(CylindricalCoordinate::class, get_class($cylindrical->asCylindrical()));
        $this->assertEquals($cylindrical->getAxis('r')->getValue(), $cylindrical->asCylindrical()->getAxis('r')->getValue());
        $this->assertEquals($cylindrical->getAxis('theta')->getValue(), $cylindrical->asCylindrical()->getAxis('theta')->getValue());
        $this->assertEquals($cylindrical->getAxis('z')->getValue(), $cylindrical->asCylindrical()->getAxis('z')->getValue());
    }

    public function testGetPlanarAngle()
    {
        $a = new CartesianCoordinate('3', '4', '5');
        $cylindrical = $a->asCylindrical();

        $this->assertEquals('0.927295218', $cylindrical->getPlanarAngle()->getValue());
    }

    public function testGetRho()
    {
        $a = new CartesianCoordinate('3', '4', '5');
        $cylindrical = $a->asCylindrical();

        $this->assertEquals('5', $cylindrical->getRho()->getValue());
    }

    public function testGetDistanceFromOrigin()
    {
        $a = new CartesianCoordinate('3', '4', '5');
        $cylindrical = $a->asCylindrical();

        $this->assertEquals('7.0710678119', $cylindrical->getDistanceFromOrigin()->getValue());
    }

    public function testDistanceTo()
    {
        $a = new CartesianCoordinate('3', '4', '5');
        $b = new CartesianCoordinate('0', '0', '0');
        $cylindrical = $a->asCylindrical();

        $this->assertEquals('7.0710678119', $cylindrical->distanceTo($b)->getValue());
    }

    public function testAsCartesian()
    {
        $a = new CartesianCoordinate('3', '4', '5');
        $cylindrical = $a->asCylindrical();
        $cartesian = $cylindrical->asCartesian();

        $this->assertEquals('3', $cartesian->getAxis('x')->getValue());
        $this->assertEquals('4', $cartesian->getAxis('y')->getValue());
        $this->assertEquals('5', $cartesian->getAxis('z')->getValue());
    }

    public function testAsSpherical()
    {
        $a = new CartesianCoordinate('3', '4', '5');
        $cylindrical = $a->asCylindrical();
        $spherical = $cylindrical->asSpherical();

        $this->assertEquals('0.7853981634', $spherical->getPolarAngle()->getValue());
        $this->assertEquals('0.927295218', $spherical->getPlanarAngle()->getValue());
        $this->assertEquals('7.0710678119', $spherical->getDistanceFromOrigin()->getValue());
    }
}
