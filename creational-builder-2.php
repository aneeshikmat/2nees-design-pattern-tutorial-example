<?php
/**
 * IMPORTANT NOTE: This example just for explain the pattern, but in real work you may need to do some action
 ** for example: in this file we use variable without setters and getters...
 *
 * This example simulate one product
 */

/**
 * This is Builder interface
 * 2nees.com
 */
interface HouseBuilderInterface2
{
    public function createWall($count): void;
    public function createRoof(): void;
    public function createDoor($count): void;
    public function createWindow($count): void;
    public function createGarage($create): void;
    public function createSwimmingPool($create): void;
    public function getResult();
}

class House2 {
    public $wall;
    public $roof;
    public $door;
    public $window;
    public $garage;
    public $swimmingPool;

    public function buildHome(): void
    {
        echo "Build {$this->wall} walls" . PHP_EOL;
        echo "Build roof" . PHP_EOL;
        echo "Build {$this->door} doors" . PHP_EOL;
        echo "Build {$this->window} window" . PHP_EOL;
        echo "Build {$this->garage} Garage" . PHP_EOL;
        echo "Build {$this->swimmingPool} Swimming Pool" . PHP_EOL;
        echo "Building Completed! Thank You!" . PHP_EOL;
    }
}

class HouseBuilder2 implements HouseBuilderInterface2 {

    private House2 $house;

    public function __construct()
    {
        $this->reset();
    }

    public function reset()
    {
        $this->house = new House2();
    }

    public function createWall($count): void
    {
        $this->house->wall = $count;
    }

    public function createRoof(): void
    {
        $this->house->roof = "Done!";
    }

    public function createDoor($count): void
    {
        $this->house->door = $count;
    }

    public function createWindow($count): void
    {
        $this->house->window = $count;
    }

    public function createGarage($create): void
    {
        $this->house->garage = $create;
    }

    public function createSwimmingPool($create): void
    {
        $this->house->swimmingPool = $create;
    }

    public function getResult(): House2
    {
        $result = $this->house;
        $this->reset();

        return $result;
    }
}

class Director2
{
    private HouseBuilderInterface2 $builder;

    public function setBuilder(HouseBuilderInterface2 $builder): void
    {
        $this->builder = $builder;
    }

    public function buildBasicHouse(): void
    {
        $this->builder->createWall(4);
        $this->builder->createRoof();
        $this->builder->createDoor(1);
        $this->builder->createWindow(2);
        $this->builder->createGarage(0);
        $this->builder->createSwimmingPool(0);
    }

    public function buildCastle(): void
    {
        $this->builder->createWall(32);
        $this->builder->createRoof();
        $this->builder->createDoor(8);
        $this->builder->createWindow(16);
        $this->builder->createGarage(1);
        $this->builder->createSwimmingPool(1);
    }
}

$director = new Director2();
$homeBuilder = new HouseBuilder2();
$director->setBuilder($homeBuilder);
$director->buildBasicHouse();
$home = $homeBuilder->getResult();
print_r($home);
$home->buildHome();
echo "=========================================" . PHP_EOL;
$director->buildCastle();
$home2 = $homeBuilder->getResult();
print_r($home2);
$home2->buildHome();