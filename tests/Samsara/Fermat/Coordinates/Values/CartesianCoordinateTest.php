<?php
namespace Samsara\Fermat\Coordinates\Values;

use PHPUnit\Framework\TestCase;
use Samsara\Exceptions\SystemError\LogicalError\IncompatibleObjectState;
use Samsara\Exceptions\UsageError\OptionalExit;
use Samsara\Fermat\Core\Numbers;
use Samsara\Fermat\Core\Values\ImmutableDecimal;

/**
 *
 */
class CartesianCoordinateTest extends TestCase
{

    /**
     * @Medium
     */
    public function testGetAxis()
    {
        $coord1 = new CartesianCoordinate(
            new ImmutableDecimal(1),
            new ImmutableDecimal(2)
        );

        $this->assertEquals('1', $coord1->getAxis(0)->getValue());
        $this->assertEquals('2', $coord1->getAxis('y')->getValue());

        $coord2 = new CartesianCoordinate(
            new ImmutableDecimal(1),
            new ImmutableDecimal(2),
            new ImmutableDecimal(5)
        );

        $this->assertEquals('1', $coord2->getAxis('x')->getValue());
        $this->assertEquals('2', $coord2->getAxis(1)->getValue());
        $this->assertEquals('5', $coord2->getAxis('z')->getValue());

        $coord3 = new CartesianCoordinate(
            new ImmutableDecimal(1),
            null,
            new ImmutableDecimal(2)
        );

        $this->assertEquals('1', $coord3->getAxis('x')->getValue());
        $this->assertEquals('2', $coord3->getAxis('y')->getValue());
        $this->assertEquals('0', $coord3->getAxis(2)->getValue());

        $coord4 = new CartesianCoordinate('1');

        $this->assertEquals('1', $coord4->getAxis('x')->getValue());
        $this->assertEquals('0', $coord4->getAxis('y')->getValue());
        $this->assertEquals('0', $coord4->getAxis('z')->getValue());
    }

    public function testDistanceFromOrigin()
    {

        $coord1 = new CartesianCoordinate(
            '3',
            '4'
        );

        $this->assertEquals('5', $coord1->getDistanceFromOrigin()->getValue());

        $coord2 = new CartesianCoordinate('3');

        $this->assertEquals('3', $coord2->getDistanceFromOrigin()->getValue());

        $coord3 = new CartesianCoordinate(
            '3',
            '4',
            '0'
        );

        $this->assertEquals('5', $coord3->getDistanceFromOrigin()->getValue());

    }

    /**
     * @medium
     */
    public function testDistanceToSpherical()
    {

        $coord1 = new CartesianCoordinate(
            '3',
            '4',
            '0'
        );

        $coord2 = new SphericalCoordinate(
            '3',
            '0',
            Numbers::makePi()->divide(2)
        );

        $coord3 = new SphericalCoordinate(
            '3',
            Numbers::makePi()->divide(2),
            '0'
        );

        $coord4 = new CartesianCoordinate(
            '0',
            '4',
            '3'
        );

        $this->assertEquals('4', $coord1->distanceTo($coord2)->getValue());
        $this->assertEquals('4', $coord4->distanceTo($coord3)->getValue());
        $this->assertEquals('5.8309518948', $coord1->distanceTo($coord3)->truncateToScale(10)->getValue());

    }

    /**
     * @medium
     */
    public function testDistanceToCylindrical()
    {

        $coord1 = new CartesianCoordinate(
            '3',
            '4',
            '0'
        );

        $coord2 = new CylindricalCoordinate(
            '3',
            '0',
            '0'
        );

        $coord3 = new CylindricalCoordinate(
            '3',
            Numbers::makePi()->divide(3),
            '2'
        );

        $this->assertEquals('4', $coord1->distanceTo($coord2)->getValue());
        $this->assertEquals('2.8662502174', $coord1->distanceTo($coord3)->truncateToScale(10)->getValue());

    }

    /**
     * @medium
     */
    public function testDistanceToPolar()
    {

        $coord1 = new CartesianCoordinate(
            '3',
            '4'
        );

        $coord2 = new PolarCoordinate(
            '3',
            '0'
        );

        $coord3 = new PolarCoordinate(
            '4',
            Numbers::makePi()->divide(2)
        );

        $coord4 = new PolarCoordinate(
            '6',
            Numbers::makePi()->divide(3)
        );

        $this->assertEquals('4', $coord1->distanceTo($coord2)->getValue());
        $this->assertEquals('3', $coord1->distanceTo($coord3)->roundToScale(10)->getValue());
        $this->assertEquals('1.1961524227', $coord1->distanceTo($coord4)->truncateToScale(10)->getValue());

    }

    public function testAsCartesian()
    {

        $coord = new CartesianCoordinate(
            '3',
            '4'
        );

        $this->assertInstanceOf(CartesianCoordinate::class, $coord->asCartesian());

    }

    public function testAsSpherical()
    {

        $coord = new CartesianCoordinate(
            '3',
            '4',
            '0'
        );

        $spherical = $coord->asSpherical();

        $this->assertEquals('5', $spherical->getAxis('rho')->getValue());
        $this->assertEquals('0.927295218', $spherical->getAxis('theta')->truncateToScale(10)->getValue());
        $this->assertEquals('1.5707963268', $spherical->getAxis('phi')->truncateToScale(10)->getValue());

        $spherical2 = $coord->asSpherical();

        $this->assertInstanceOf(SphericalCoordinate::class, $spherical2);
        $this->assertEquals('5', $spherical2->getAxis('rho')->getValue());
        $this->assertEquals('0.927295218', $spherical2->getAxis('theta')->truncateToScale(10)->getValue());
        $this->assertEquals('1.5707963268', $spherical2->getAxis('phi')->truncateToScale(10)->getValue());

        $coord2 = new CartesianCoordinate(
            '3',
            '4'
        );

        $this->expectException(IncompatibleObjectState::class);
        $coord2->asSpherical();

    }

    public function testAsCylindrical()
    {

        $coord = new CartesianCoordinate(
            '3',
            '4',
            '0'
        );

        $cylindrical = $coord->asCylindrical();

        $this->assertEquals('5', $cylindrical->getAxis('r')->truncateToScale(10)->getValue());
        $this->assertEquals('0.927295218', $cylindrical->getAxis('theta')->truncateToScale(10)->getValue());
        $this->assertEquals('0', $cylindrical->getAxis('z')->getValue());

        $coord2 = new CartesianCoordinate(
            '3',
            '4'
        );

        $this->expectException(IncompatibleObjectState::class);
        $coord2->asCylindrical();

    }

    public function testAsPolar()
    {

        $coord = new CartesianCoordinate(
            '3',
            '4'
        );

        $cylindrical = $coord->asPolar();

        $this->assertEquals('5', $cylindrical->getAxis('rho')->truncateToScale(10)->getValue());
        $this->assertEquals('0.927295218', $cylindrical->getAxis('theta')->truncateToScale(10)->getValue());

        $coord = new CartesianCoordinate(
            '3',
            '-4'
        );

        $cylindrical = $coord->asPolar();

        $this->assertEquals('5', $cylindrical->getAxis('rho')->truncateToScale(10)->getValue());
        $this->assertEquals('5.355890089', $cylindrical->getAxis('theta')->truncateToScale(10)->getValue());

        $coord = new CartesianCoordinate(
            '0',
            '-4'
        );

        $cylindrical = $coord->asPolar();

        $this->assertEquals('4', $cylindrical->getAxis('rho')->truncateToScale(10)->getValue());
        $this->assertEquals('4.71238898', $cylindrical->getAxis('theta')->truncateToScale(10)->getValue());

    }

    public function testAsPolarExceptionsOne()
    {
        $coord3 = new CartesianCoordinate(
            '0',
            '0'
        );

        $this->expectException(OptionalExit::class);
        $coord3->asPolar();
    }

    public function testAsPolarExceptionsTwo()
    {
        $coord2 = new CartesianCoordinate(
            '3',
            '4',
            '0'
        );

        $this->expectException(IncompatibleObjectState::class);
        $coord2->asPolar();
    }


    public function testGetPolarAngle()
    {

        $coord = new CartesianCoordinate(
            '3',
            '4'
        );

        $this->assertEquals('0.927295218', $coord->getPolarAngle()->truncateToScale(10)->getValue());

        $coord2 = new CartesianCoordinate(
            '3',
            '4',
            '0'
        );

        $this->assertEquals('1.5707963268', $coord2->getPolarAngle()->truncateToScale(10)->getValue());

        $coord3 = new CartesianCoordinate('1');

        $this->expectException(IncompatibleObjectState::class);
        $coord3->getPolarAngle();

    }

    public function testGetPlanarAngle()
    {

        $coord = new CartesianCoordinate(
            '3',
            '4'
        );

        $this->assertEquals('0.927295218', $coord->getPlanarAngle()->truncateToScale(10)->getValue());

        $coord2 = new CartesianCoordinate(
            '3',
            '4',
            '0'
        );

        $this->assertEquals('0.927295218', $coord2->getPlanarAngle()->truncateToScale(10)->getValue());

        $coord3 = new CartesianCoordinate('1');

        $this->expectException(IncompatibleObjectState::class);
        $coord3->getPlanarAngle();

    }

}