<?php
/**
 * This example just to simulate how Memento can be work
 * 2nees.com
 */

// Fake Status List
const STATUS = [
    "ACTIVE",
    "PENDING",
    "REJECTED",
    "APPROVED",
    "RUNNING",
    "IN_PROGRESS",
    "DELETED",
    "HOLD"
];

/**
 * Memento Interface which its used to retrive meta data only, keep in you mind you must prevent access state from
 ** any class except the Originator
 */
interface MementoTicket
{
    public function getTicketName(): string;
    public function getCreatedAt(): string;
}

/**
 * Class TicketOriginator - This is the Originator which its the owner ob state and this class save and restore data
 ** from Memento
 */
class TicketOriginator
{
    private int $ticketNumber;
    private string $ticketOwner;
    private string $ticketStatus;

    /**
     * TicketOriginator constructor.
     * @param int $ticketNumber
     * @param string $ticketOwner
     */
    public function __construct(int $ticketNumber, string $ticketOwner)
    {
        $this->ticketNumber = $ticketNumber;
        $this->ticketOwner = $ticketOwner;
        $this->ticketStatus = STATUS[rand(0, 7)];
    }

    /**
     * @return string
     */
    public function getTicketStatus(): string
    {
        return $this->ticketStatus;
    }

    /**
     * @param string $ticketStatus
     */
    public function setTicketStatus(string $ticketStatus): void
    {
        $this->ticketStatus = $ticketStatus;
    }

    /**
     * @return int
     */
    public function getTicketNumber(): int
    {
        return $this->ticketNumber;
    }

    /**
     * @param int $ticketNumber
     */
    public function setTicketNumber(int $ticketNumber): void
    {
        $this->ticketNumber = $ticketNumber;
    }

    /**
     * @return string
     */
    public function getTicketOwner(): string
    {
        return $this->ticketOwner;
    }

    /**
     * @param string $ticketOwner
     */
    public function setTicketOwner(string $ticketOwner): void
    {
        $this->ticketOwner = $ticketOwner;
    }

    public function takeSnapShot(): MementoTicket
    {
        return new TicketConcreteMemento(clone $this);
    }

    public function restoreState(MementoTicket $mementoTicket)
    {
        $state = $mementoTicket->getState();
        $this->setTicketNumber($state->getTicketNumber());
        $this->setTicketOwner($state->getTicketOwner());
        $this->setTicketStatus($state->getTicketStatus());
    }
}

/**
 * Class TicketConcreteMemento - this class Concrete for memento, implement method and contain infrastructure for store states...
 * Important Note: This class must initialize from Constructor, Dont create a setter function!
 */
class TicketConcreteMemento implements MementoTicket
{
    private TicketOriginator $state;
    private $date;

    public function __construct(TicketOriginator $state)
    {
        $this->state = $state;
        $this->date = date('Ymd');
    }

    public function getState(): TicketOriginator
    {
        return $this->state;
    }

    /**
     * Some of metadata may used from Originator
     */
    public function getTicketName(): string
    {
        return "memento-state-{$this->date}";
    }

    public function getCreatedAt(): string
    {
        return $this->date;
    }
}

/**
 * Class TicketActions - This is class is Caretaker, and its refer to care about history for this state, so that
 ** its know about metadata from memento, but this class cant do any action on any state saved in memento,
 ** so that, this class save state history for originator.
 */
class TicketActions
{
    private ?array $statesHistory;
    private TicketOriginator $ticketOriginator;

    /**
     * TicketActions constructor.
     * @param TicketOriginator $ticketOriginator
     */
    public function __construct(TicketOriginator $ticketOriginator)
    {
        $this->ticketOriginator = $ticketOriginator;
    }

    public function backup()
    {
        $state = $this->ticketOriginator->takeSnapShot();
        $this->statesHistory[] = $this->ticketOriginator->takeSnapShot();
        $this->printDetails("Backup Saved", $state);
    }

    public function restore()
    {
        if(!count($this->statesHistory)){
            echo "You cant restore empty data" . PHP_EOL;
            return;
        }

        $state = array_pop($this->statesHistory);
        $this->ticketOriginator->restoreState($state);
        $this->printDetails("Restored", $state);
    }

    private function printDetails($action, $state): void
    {
        echo "{$action}: 
            #{$state->getCreatedAt()}/{$state->getTicketName()}
            - {$this->ticketOriginator->getTicketNumber()}/{$this->ticketOriginator->getTicketOwner()}
            - With Status {$this->ticketOriginator->getTicketStatus()}" . PHP_EOL;
    }
}

// Client
$ticketOriginator = new TicketOriginator(rand(1, 200), "Anees");
$ticketActions = new TicketActions($ticketOriginator);

$ticketActions->backup();// Save init data
$ticketOriginator->setTicketNumber(rand(200, 400));
$ticketOriginator->setTicketOwner("Hikmat");
$ticketOriginator->setTicketStatus(STATUS[rand(0, 7)]);
$ticketActions->backup();// Save first change
$ticketOriginator->setTicketStatus(STATUS[rand(0, 7)]);
$ticketOriginator->setTicketOwner("2nees.com");
$ticketActions->backup();// Save second change
$ticketOriginator->setTicketNumber(rand(500, 600));// not saved, else if you uncomment bellow...
//$ticketActions->backup(); // Uncomment if you like to save latest random number

echo "Latest Update: " . $ticketOriginator->getTicketNumber() . PHP_EOL;// will print new value, but not saved since latest backup is commented

// OOPS, its mistake, lets restore old data...
$ticketActions->restore();
$ticketActions->restore();
$ticketActions->restore();
$ticketActions->restore();
