<?php
/**
 * This example just to simulate how Composite can be work
 * 2nees.com
 */

/**
 * Common Operations For all objects
 * Interface OrderInterface
 */
interface OrderInterface {
    public function render(): string;
}

/**
 * Leaf class
 * Class OrderItems
 */
class OrderItems implements OrderInterface {
    private string $itemName;
    private string $itemID;

    /**
     * OrderItems constructor.
     * @param string $itemName
     * @param string $itemID
     */
    public function __construct(string $itemName, string $itemID)
    {
        $this->itemName = $itemName;
        $this->itemID = $itemID;
    }

    public function render(): string
    {
        return "{$this->itemID}: {$this->itemName}";
    }
}

/**
 * Leaf class
 * Class OrderOwner
 */
class OrderOwner implements OrderInterface {
    private string $name;
    private string $phone;

    /**
     * OrderItems constructor.
     * @param string $name
     * @param string $phone
     */
    public function __construct(string $name, string $phone)
    {
        $this->name = $name;
        $this->phone = $phone;
    }

    public function render(): string
    {
        return "Order Owner: {$this->name}:{$this->phone}";
    }
}

class Composite implements OrderInterface {
    private SplObjectStorage $members;

    /**
     * Composite constructor.
     */
    public function __construct()
    {
        $this->members = new \SplObjectStorage();
    }

    /**
     * @param OrderInterface $member
     */
    public function addMember(OrderInterface $member){
        $this->members->attach($member);
    }

    /**
     * @param OrderInterface $member
     */
    public function removeMember(OrderInterface $member){
        $this->members->detach($member);
    }

    public function render(): string
    {
        $result = [];
        foreach ($this->members as $member){
            array_push($result, $member->render());
        }

        return json_encode($result, JSON_PRETTY_PRINT);
    }
}

$comp1 = new Composite();
$comp1->addMember(new OrderItems("Car", 20));
$comp1->addMember(new OrderOwner("Anees", 1234687));
print_r($comp1->render());
echo PHP_EOL . "==================================================" . PHP_EOL;

$comp2 = new Composite();
$comp2->addMember(new OrderItems("Bike", 15));
$comp2->addMember(new OrderOwner("Taher", 66234));
print_r($comp2->render());
echo PHP_EOL. "==================================================" . PHP_EOL;

$comp3  = new Composite();
$comp3->addMember($comp1);
$comp3->addMember($comp2);
print_r($comp3->render());