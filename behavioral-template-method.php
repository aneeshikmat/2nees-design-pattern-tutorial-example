<?php
/**
 * This example just to simulate how Template Method can be work
 * 2nees.com
 */

/**
 * Class CoffeeMachine - The Abstract Class defines a template method that contains a skeleton of some algorithm
 */
abstract class CoffeeMachine {
    // This is the template which its define the skeleton of an algorithm - you cant update or change this template from subclass
    final public function prepareCoffee(): void {
        echo "Preparing Start..." . PHP_EOL;
        echo $this->addCup() . PHP_EOL;
        echo $this->addWater() . PHP_EOL;
        echo $this->addMilk() . PHP_EOL;
        echo $this->addSugar() . PHP_EOL;
        echo "Done! you can got your Cup!" . PHP_EOL;
    }
    
    abstract protected function addWater(): string;
    abstract protected function addSugar(): string;
    abstract protected function addMilk(): string;
    // Step, but can override!, this is the default cub for all subClasses...but its can override
    protected function addCup(): string {
        return "Add Carton Cub!";
    }
}

#region Concrete Template Method - these class will implement different variations of Coffee type base on CoffeeMachine
class Coffee extends CoffeeMachine {
    protected function addWater(): string
    {
        return "Add Hot Water to Coffee";
    }

    protected function addSugar(): string
    {
        return "Add Sugar to Coffee";
    }

    protected function addMilk(): string
    {
        return "skip milk...=>";// No milk on coffee
    }
}

class HotChocolate extends CoffeeMachine {
    protected function addWater(): string
    {
        return "Add Hot Water to HotChocolate";
    }

    protected function addSugar(): string
    {
        return "skip sugar...=>";// No Sugar on HotChocolate
    }

    protected function addMilk(): string
    {
        return "Add Milk";
    }
}

class IceCoffee extends CoffeeMachine {
    protected function addWater(): string
    {
        return "Add Cold Water to IceCoffee";
    }

    protected function addSugar(): string
    {
        return "Add a little bit of Sugar";
    }

    protected function addMilk(): string
    {
        return "Add Milk";
    }

    protected function addCup(): string {
        return "Add Plastic Cub!";
    }
}
#endregion

// Client
$coffee = new Coffee();
$coffee->prepareCoffee();
echo "============================" . PHP_EOL;
$hotChocolate = new HotChocolate();
$hotChocolate->prepareCoffee();
echo "============================" . PHP_EOL;
$iceCoffee = new IceCoffee();
$iceCoffee->prepareCoffee();