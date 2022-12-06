<?php

namespace App\Tests\UnitTests\Entity;

use App\Entity\Lot;
use App\Entity\Vehicle;
use DateTime;
use PHPUnit\Framework\TestCase;

class VehicleTest extends TestCase
{
    private Vehicle $vehicle;

    protected function setUp(): void
    {
        $this->vehicle = new Vehicle();

        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->vehicle = new Vehicle();
    }

    /**
     * @dataProvider getIdDataProvider
     */
    public function testGetId(int $id): void
    {
        $this->vehicle->setId($id);

        $this->assertEquals($id, $this->vehicle->getId());
    }

    public function getIdDataProvider(): array
    {
        return [
            [1],
            [2],
            [3],
        ];
    }

    /**
     * @dataProvider getMakeDataProvider
     */
    public function testGetMake(string $make): void
    {
        $this->vehicle->setMake($make);

        $this->assertEquals($make, $this->vehicle->getMake());
    }

    public function getMakeDataProvider(): array
    {
        return [
            ['BMW'],
            ['Audi'],
            ['Mercedes'],
        ];
    }

    /**
     * @dataProvider getModelDataProvider
     */
    public function testGetModel(string $model): void
    {
        $this->vehicle->setModel($model);

        $this->assertEquals($model, $this->vehicle->getModel());
    }

    public function getModelDataProvider(): array
    {
        return [
            ['Golf'],
            ['A3'],
            ['C class'],
        ];
    }

    /**
     * @dataProvider getDateOfManufactureDataProvider
     */
    public function testGetDateOfManufacture(DateTime $date): void
    {
        $this->vehicle->setDateOfManufacture($date);

        $this->assertEquals($date, $this->vehicle->getDateOfManufacture());
    }

    public function getDateOfManufactureDataProvider(): array
    {
        return [
            [new DateTime('1996-01-01')],
            [new DateTime('2018-01-02')],
            [new DateTime('2022-01-03')],
        ];
    }

    /**
     * @dataProvider getFuelTypeDataProvider
     */
    public function testGetFuelType(string $fuelType): void
    {
        $this->vehicle->setFuelType($fuelType);

        $this->assertEquals($fuelType, $this->vehicle->getFuelType());
    }

    public function getFuelTypeDataProvider(): array
    {
        return [
            ['Diesel'],
            ['Petrol'],
            ['Electric'],
        ];
    }

    /**
     * @dataProvider getGearboxDataProvider
     */
    public function testGetGearbox(string $gearbox): void
    {
        $this->vehicle->setGearbox($gearbox);

        $this->assertEquals($gearbox, $this->vehicle->getGearbox());
    }

    public function getGearboxDataProvider(): array
    {
        return [
            ['Manual'],
            ['Automatic'],
        ];
    }

    /**
     * @dataProvider getEngineCapacityDataProvider
     */
    public function testGetEngineCapacity(int $capacity): void
    {
        $this->vehicle->setEngineCapacity($capacity);

        $this->assertEquals($capacity, $this->vehicle->getEngineCapacity());
    }

    public function getEngineCapacityDataProvider(): array
    {
        return [
            [1],
            [2],
            [3],
        ];
    }

    /**
     * @dataProvider getLotDataProvider
     */
    public function testGetLot(?Lot $lot): void
    {
        $this->vehicle->setLot($lot);

        $this->assertEquals($lot, $this->vehicle->getLot());
    }

    public function getLotDataProvider(): array
    {
        return [
            [null],
            [(new Lot())->setId(1)],
        ];
    }
}