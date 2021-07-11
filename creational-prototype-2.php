<?php

/**
 * Prototype interface - This abstract contain clone magic method
 * 2nees.com
 */
abstract class Shape {
    private int $x;
    private int $y;

    /**
     * @param int $x
     */
    public function setX(int $x): void
    {
        $this->x = $x;
    }

    /**
     * @param int $y
     */
    public function setY(int $y): void
    {
        $this->y = $y;
    }

    abstract public function __clone();
}

/**
 * Simple Class for simulate Concrete Prototype
 * 2nees.com
 */
class Rectangle extends Shape {
    public function __clone()
    {
        return new Rectangle();
    }
}

/**
 * Simple Class for simulate Concrete Prototype
 * 2nees.com
 */
class Circle extends Shape {
    public int $radius;

    public function __clone()
    {
        return new Circle();
    }
}

/**
 * Simple Class for simulate Concrete Prototype
 * 2nees.com
 */
class Square extends Shape {
    private int $position;

    public function __construct() {
        $this->position = 1;
        $this->setX(1);
        $this->setY(1);
    }

    public function __clone()
    {
        $size = rand(10, 20);
        $this->position = rand(2, 40);
        $this->setX($size);
        $this->setY($size);
        return $this;
    }
}

$shapes = [];

$newCircle = new Circle();
$newCircle->radius = 20;
$newCircle->setX(5);
$newCircle->setY(5);

$newRect = new Rectangle();
$newRect->setX(10);
$newRect->setY(10);

$newSq = new Square();

$shapes[] = $newCircle;
$shapes[] = $newRect;
$shapes[] = $newSq;

for ($i = 1; $i <= 3; $i++){
    $shapes[] = clone $newCircle;
    $shapes[] = clone $newRect;
    $shapes[] = clone $newSq;
}

print_r($shapes);