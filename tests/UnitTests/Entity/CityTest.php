<?php

namespace App\Tests\UnitTests\Entity;

use App\Entity\City;
use App\Entity\Lot;
use PHPUnit\Framework\TestCase;

class CityTest extends TestCase
{
    private City $city;

    protected function setUp(): void
    {
        $this->city = new City();

        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->city = new City();
    }

    /**
     * @dataProvider getIdDataProvider
     */
    public function testGetId(int $id): void
    {
        $this->city->setId($id);

        $this->assertEquals($id, $this->city->getId());
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
     * @dataProvider getNameDataProvider
     */
    public function testGetName(string $name): void
    {
        $this->city->setName($name);

        $this->assertEquals($name, $this->city->getName());
    }

    public function getNameDataProvider(): array
    {
        return [
            ['Klaipeda'],
            ['Kaunas'],
            ['Vilnius'],
        ];
    }

    /**
     * @dataProvider getLotDataProvider
     */
    public function testGetLot(array $lots): void
    {
        foreach ($lots as $lot) {
            $this->city->addLot($lot);
        }

        $this->assertEquals($lots, $this->city->getLots()->toArray());

        foreach ($lots as $lot) {
            $this->city->removeLot($lot);
        }

        $this->assertEquals([], $this->city->getLots()->toArray());
    }

    public function getLotDataProvider(): \Generator
    {
        yield [
                [
                    (new Lot())->setName('Lot1'),
                    (new Lot())->setName('Lot2'),
                    (new Lot())->setName('Lot3')
                ],
        ];
    }
}