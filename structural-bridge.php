<?php
/**
 * This example just to simulate how Bridge can be work
 * 2nees.com
 */

/**
 * Abstraction Part for handling one of two hierarchies of classes
 * 2nees.com
 */
abstract class Shape
{
    private string $name;
    protected Color $color;

    public function __construct(Color $color)
    {
        $this->changeColorRenderer($color);
    }

    public function changeColorRenderer(Color $color): void
    {
        $this->color = $color;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    abstract public function shapeDetails(): void;
}

/**
 * Concrete Abstraction represents Circle
 * 2nees.com
 */
class Circle extends Shape
{
    public function __construct(Color $color)
    {
        parent::__construct($color);
        $this->setName("Circle");
    }

    public function shapeDetails(): void
    {
        echo "{$this->getName()} Filled with this color: {$this->color->getFillColor()}" . PHP_EOL;
    }
}

/**
 * Concrete Abstraction represents Rect
 * 2nees.com
 */
class Rect extends Shape
{
    public function __construct(Color $color)
    {
        parent::__construct($color);
        $this->setName("Rect");
    }

    public function shapeDetails(): void
    {
        echo "{$this->getName()} Filled with this color: {$this->color->getFillColor()} With Border Color: {$this->color->getBorderColor()}" . PHP_EOL;
    }
}

/**
 * The Implementation Part for declare needed method which can access via Abstraction
 * Interface Color
 */
interface Color
{
    public function getFillColor(): string;

    public function getBorderColor(): string;
}

/**
 * Concrete Implementation render Red Colors
 * 2nees.com
 */
class Red implements Color
{
    public function getFillColor(): string
    {
        return "#ff0000";
    }

    public function getBorderColor(): string
    {
        return "#000000";
    }
}

/**
 * Concrete Implementation render Blue Colors
 * 2nees.com
 */
class Blue implements Color
{
    public function getFillColor(): string
    {
        return "#0000ff";
    }

    public function getBorderColor(): string
    {
        return "#ffff00";
    }
}

// Call Concrete Implementation To Passing it to Concrete Abstraction
$redColor = new Red();
$blueColor = new Blue();

// Create Circle
$myCircleShape = new Circle($redColor);
$myCircleShape->shapeDetails();
// Change Color on Run time
$myCircleShape->changeColorRenderer($blueColor);
$myCircleShape->shapeDetails();

// Create Rect
$myRectShape = new Rect($redColor);
$myRectShape->shapeDetails();
// Change Rect on Run time
$myRectShape->changeColorRenderer($blueColor);
$myRectShape->shapeDetails();