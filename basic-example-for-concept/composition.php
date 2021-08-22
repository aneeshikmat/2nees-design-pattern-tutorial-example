<?php
/**
 * This example just to simulate how Composition can be work
 * 2nees.com
 */

class Person {
    private Heart $heart;
    private Brain $brain;

    /**
     * Person constructor.
     * @param Heart $heart
     * @param Brain $brain
     */
    public function __construct(Heart $heart, Brain $brain)
    {
        $this->heart = $heart;
        $this->brain = $brain;
    }

    public function printPersonDetails(): void {
        echo "
            Heart is {$this->heart->getStatus()} And 
            Brain is {$this->brain->getStatus()}
        ";
    }
}

abstract class Members {
    private string $status;

    /**
     * Heart constructor.
     * @param string $status
     */
    public function __construct(string $status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}

class Heart extends Members {
    // Do any thing...
}

class Brain extends Members {
    // Do any thing...
}

// Client
$heart = new Heart("Strong");
$brain = new Brain("Smart");

$person = new Person($heart, $brain);
$person->printPersonDetails();