<?php
/**
 * This example just to simulate how Observer can be work And how we can build a group of events
 * 2nees.com
 */

const COMMON_EVENTS_GROUP = "*";
const CUSTOMER_SET_DOWN = "customer:set";
const INVOICE_GROUP = "accountant";

/**
 * Class Restaurant - Publisher (Subject) - subscription infrastructure that lets new subscribers join/left
 */
class Restaurant implements SplSubject {
    private array $observers;
    private int $tableNumber = 0;
    private int $numberOfCustomer = 0;

    /**
     * Restaurant constructor.
     */
    public function __construct()
    {
        $this->observers['*'] = [];
    }

    /**
     * @param string $event
     */
    private function initEventGroup(string $event = COMMON_EVENTS_GROUP): void
    {
        if (!isset($this->observers[$event])) {
            $this->observers[$event] = [];
        }
    }

    /**
     * Merge Common events with spesfic events
     *
     * @param string $event
     * @return array
     */
    private function getEventObservers(string $event = COMMON_EVENTS_GROUP): array
    {
        $this->initEventGroup($event);
        $group = $this->observers[$event];
        $all = $this->observers[COMMON_EVENTS_GROUP];

        return array_merge($group, $all);
    }

    /**
     * @param SplObserver $observer
     * @param string $event
     */
    public function attach(SplObserver $observer, string $event = COMMON_EVENTS_GROUP)
    {
        // Init event group if not exsits
        $this->initEventGroup($event);
        // Build key
        $key = spl_object_hash($observer);
        // Assign new object
        $this->observers[$event][$key] = $observer;
    }

    public function detach(SplObserver $observer, string $event = COMMON_EVENTS_GROUP)
    {
        foreach ($this->getEventObservers($event) as $key => $ob){
            if($key === spl_object_hash($ob)){
                unset($this->observers[$event][$key]);
            }
        }
    }

    public function notify(string $event = COMMON_EVENTS_GROUP)
    {
        foreach ($this->getEventObservers($event) as $observer) {
            $observer->update($this, $event);
        }
    }

    /**
     * Logic set here, we add notify if customer set down on table
     */
    public function customerSetOnTable(){
        $this->numberOfCustomer = rand(1, 5);
        $this->tableNumber += 1;

        $this->notify(CUSTOMER_SET_DOWN);
        $this->createInvoice();
    }

    /**
     * Logic set here, we add notify if customer set down on table
     */
    public function createInvoice(){
        $this->notify(INVOICE_GROUP);
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
    public function update(SplSubject $subject, string $event = null)
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
    public function update(SplSubject $subject, string $event = null)
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
    public function update(SplSubject $subject, string $event = null)
    {
        /**
         * @var Restaurant $subject
         */
        $numberOfCustomer = $subject->getNumberOfCustomer();
        $initPrice = $numberOfCustomer * 0.5;// Number of user * half JOD

        echo PHP_EOL. "Hay!, Invoice Start for 
         Table Number #{$subject->getTableNumber()}
         And We have {$numberOfCustomer} Customer
         And total init price is {$initPrice} JOD
         " . PHP_EOL;
    }
}

/**
 * Class Accountant - Concrete Subscribers - implement some action when notify received...
 */
class Logger implements SplObserver {
    public function update(SplSubject $subject, string $event = null)
    {
        /**
         * @var Restaurant $subject
         */

        echo "TrackHistory: {$event} - {$subject->getTableNumber()}" . PHP_EOL;
    }
}

// Client
$restaurant = new Restaurant();
$kitchen = new Kitchen();
$waiter= new Waiter();
$accountant = new Accountant();
$logger = new Logger();

$restaurant->attach($logger);
$restaurant->attach($waiter, CUSTOMER_SET_DOWN);
$restaurant->attach($kitchen, CUSTOMER_SET_DOWN);
$restaurant->attach($accountant, INVOICE_GROUP);

$restaurant->customerSetOnTable();
echo "=======================================" . PHP_EOL;
$restaurant->customerSetOnTable();
echo "=======================================" . PHP_EOL;
$restaurant->customerSetOnTable();