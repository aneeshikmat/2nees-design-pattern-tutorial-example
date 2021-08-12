<?php
/**
 * This example just to simulate how Observer can be work
 * 2nees.com
 */

/**
 * Class Restaurant - Publisher (Subject) - subscription infrastructure that lets new subscribers join/left
 */
class Restaurant implements SplSubject {
    private SplObjectStorage $observers;
    private int $tableNumber = 0;
    private int $numberOfCustomer = 0;

    /**
     * Restaurant constructor.
     */
    public function __construct()
    {
        $this->observers = new SplObjectStorage();
    }

    public function attach(SplObserver $observer)
    {
        $this->observers->attach($observer);
    }

    public function detach(SplObserver $observer)
    {
        $this->observers->detach($observer);
    }

    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    /**
     * Logic set here, we add notify if customer set down on table
     */
    public function customerSetOnTable(){
        $this->numberOfCustomer = rand(1, 5);
        $this->tableNumber += 1;

        $this->notify();
    }

    public function getTableNumber(): int
    {
        return $this->tableNumber;
    }

    public function getNumberOfCustomer(): int
    {
        return $this->numberOfCustomer;
    }
}

/**
 * Class Waiter - Concrete Subscribers - implement some action when notify received...
 */
class Waiter implements SplObserver {
    public function update(SplSubject $subject)
    {
        /**
         * @var Restaurant $subject
         */
        echo "Hay!, We have a new customer here, check Table Number #{$subject->getTableNumber()}" . PHP_EOL;
    }
}

/**
 * Class Kitchen - Concrete Subscribers - implement some action when notify received...
 */
class Kitchen implements SplObserver {
    public function update(SplSubject $subject)
    {
        /**
         * @var Restaurant $subject
         */
        echo "Hay!, We have an order for
         Table Number #{$subject->getTableNumber()}
         And We have {$subject->getNumberOfCustomer()} Customer" . PHP_EOL;
    }
}

/**
 * Class Accountant - Concrete Subscribers - implement some action when notify received...
 */
class Accountant implements SplObserver {
    public function update(SplSubject $subject)
    {
        /**
         * @var Restaurant $subject
         */
        $numberOfCustomer = $subject->getNumberOfCustomer();
        $initPrice = $numberOfCustomer * 0.5;// Number of user * half JOD

        echo "Hay!, Invoice Start for 
         Table Number #{$subject->getTableNumber()}
         And We have {$numberOfCustomer} Customer
         And total init price is {$initPrice} JOD
         " . PHP_EOL;
    }
}

// Client
$restaurant = new Restaurant();
$kitchen = new Kitchen();
$waiter= new Waiter();
$accountant = new Accountant();

$restaurant->attach($waiter);
$restaurant->attach($kitchen);
$restaurant->attach($accountant);

$restaurant->customerSetOnTable();
echo "=======================================" . PHP_EOL;
$restaurant->customerSetOnTable();
echo "=======================================" . PHP_EOL;
$restaurant->customerSetOnTable();