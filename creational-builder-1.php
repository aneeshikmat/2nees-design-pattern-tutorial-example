<?php
/**
 * IMPORTANT NOTE: This example just for explain the pattern, but in real work you may need to do some action
 ** for example in house abstract or via union products or to use variable without setters and getters...
 ** and make more than one build, but this example just
 * to simulate build Builder Pattern with fully Idea...
 *
 * This example simulate two products
 */

/**
 * This is Builder interface
 * 2nees.com
 */
interface HouseBuilderInterface
{
    public function createWall(): void;
    public function createRoof(): void;
    public function createDoor(): void;
    public function createWindow(): void;
    public function createGarage(): void;
    public function createSwimmingPool(): void;
    public function getResult();
}

abstract class HouseAbstracts {
    public $wall;
    public $roof;
    public $door;
    public $window;
    public $garage;
    public $swimmingPool;

    public abstract function buildHome() : void;
}

class House extends HouseAbstracts {
    public function buildHome(): void
    {
        echo "Build {$this->wall} walls" . PHP_EOL;
        echo "Build roof" . PHP_EOL;
        echo "Build {$this->door} doors" . PHP_EOL;
        echo "Build {$this->window} window" . PHP_EOL;
        echo "Building House Completed! Thank You!" . PHP_EOL;
    }
}

class Castle extends HouseAbstracts {
    public function buildHome(): void
    {
        echo "Build {$this->wall} walls" . PHP_EOL;
        echo "Build roof" . PHP_EOL;
        echo "Build {$this->door} doors" . PHP_EOL;
        echo "Build {$this->window} window" . PHP_EOL;
        echo "Build Garage" . PHP_EOL;
        echo "Build Swimming Pool" . PHP_EOL;
        echo "Building Castle Completed! Thank You!" . PHP_EOL;
    }
}

class HouseBuilder implements HouseBuilderInterface {

    private House $house;

    public function __construct()
    {
        $this->reset();
    }

    public function reset()
    {
        $this->house = new House();
    }

    public function createWall(): void
    {
       $this->house->wall = 4;
    }

    public function createRoof(): void
    {
        $this->house->roof = "Done!";
    }

    public function createDoor(): void
    {
        $this->house->door = 1;
    }

    public function createWindow(): void
    {
        $this->house->window = 2;
    }

    public function createGarage(): void
    {
       $this->house->garage = false;
    }

    public function createSwimmingPool(): void
    {
        $this->house->swimmingPool = false;
    }

    public function getResult(): House
    {
        $result = $this->house;
        $this->reset();

        return $result;
    }
}

class CastleBuilder implements HouseBuilderInterface {

    private Castle $castle;

    public function __construct()
    {
        $this->reset();
    }

    public function reset()
    {
        $this->castle = new Castle();
    }

    public function createWall(): void
    {
       $this->castle->wall = 32;
    }

    public function createRoof(): void
    {
        $this->castle->roof = "Done!";
    }

    public function createDoor(): void
    {
        $this->castle->door = 8;
    }

    public function createWindow(): void
    {
        $this->castle->window = 16;
    }

    public function createGarage(): void
    {
        $this->castle->garage = true;
    }

    public function createSwimmingPool(): void
    {
        $this->castle->swimmingPool = true;
    }

    public function getResult(): Castle
    {
        $result = $this->castle;
        $this->reset();

        return $result;
    }
}

class Director
{
    private HouseBuilderInterface $builder;

    public function setBuilder(HouseBuilderInterface $builder): void
    {
        $this->builder = $builder;
    }

    public function buildYourHouse(): void
    {
        $this->builder->createWall();
        $this->builder->createRoof();
        $this->builder->createDoor();
        $this->builder->createWindow();
        $this->builder->createGarage();
        $this->builder->createSwimmingPool();
    }
}

$director = new Director();
$homeBuilder = new HouseBuilder();
$director->setBuilder($homeBuilder);
$director->buildYourHouse();
$home = $homeBuilder->getResult();
print_r($home);
$home->buildHome();
echo "=========================================" . PHP_EOL;
$castleBuilder = new CastleBuilder();
$director->setBuilder($castleBuilder);
$director->buildYourHouse();
$castle = $castleBuilder->getResult();
print_r($castle);
$castle->buildHome();