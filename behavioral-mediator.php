<?php
/**
 * This example just to simulate how Mediator can be work
 * 2nees.com
 */

const UP = "UP";
const DOWN = "DOWN";
const FIRE = "FIRE";
const SAFE = "SAFE";

/**
 * Interface PilotsMediator - Mediator Interface
 */
interface PilotsMediator {
    public function notify(BaseComponent $sender, string $event);
}

/**
 * The Base Component provides the basic functionality of storing a mediator's
 */
abstract class BaseComponent
{
    protected ?ControlTower $controlTower;

    public function setControlTower(ControlTower $controlTower): void
    {
        $this->controlTower = $controlTower;
    }
}

#region Components - Various classes that contain some business logic
class Helicopter extends BaseComponent {
    public function helicopterUP()
    {
        $this->controlTower->notify($this, UP);
    }

    public function helicopterDOWN()
    {
        $this->controlTower->notify($this, DOWN);
    }

    // Do needed job base on actions or base on needed details for this class...
}

class Airbus extends BaseComponent {
    public function airbusUP()
    {
        $this->controlTower->notify($this, UP);
    }

    public function airbusDOWN()
    {
        $this->controlTower->notify($this, DOWN);
    }

    // Do needed job base on actions or base on needed details for this class...
}

class Emergency extends BaseComponent {
    private bool $isSafe = true;

    public function fireAlarm(){
        $this->isSafe = false;
        $this->controlTower->notify($this, FIRE);
    }

    /**
     * @return bool
     */
    public function isSafe(): bool
    {
        return $this->isSafe;
    }

    /**
     * @param bool $isSafe
     */
    public function setIsSafe(bool $isSafe): void
    {
        $this->isSafe = $isSafe;
    }
}

class SafetyTeam extends BaseComponent {
    public function safeAlarm(){
        $this->controlTower->notify($this, SAFE);
    }

    // Do needed job base on actions or base on needed details for this class...
}
#endregion

/**
 * Class ControlTower - Concrete Mediators which its contain relations between various components
 */
class ControlTower implements PilotsMediator {
    private Helicopter $helicopter;
    private Airbus $airbus;
    private Emergency $emergency;
    private SafetyTeam $safetyTeam;

    /**
     * ControlTower constructor.
     * @param Helicopter $helicopter
     * @param Airbus $airbus
     */
    public function __construct(Helicopter $helicopter, Airbus $airbus, Emergency $emergency, SafetyTeam $safetyTeam)
    {
        $this->helicopter = $helicopter;
        $this->helicopter->setControlTower($this);
        $this->airbus = $airbus;
        $this->airbus->setControlTower($this);
        $this->emergency = $emergency;
        $this->emergency->setControlTower($this);
        $this->safetyTeam = $safetyTeam;
        $this->safetyTeam->setControlTower($this);
    }

    public function notify(BaseComponent $sender, string $event)
    {
        if($sender instanceof Airbus){
            if(!$this->emergency->isSafe()){
                echo "Airbus keep fly!, no action since we have emergency Alarm!" . PHP_EOL;
                return;
            }

            if($event === UP){
                echo "Airbus: Wait until road is empty!" . PHP_EOL;
            }else {
                $roadNumber = rand(100, 400);
                echo "Airbus: Go to Road Number #{$roadNumber}" . PHP_EOL;
            }
        }else if($sender instanceof Helicopter){
            if(!$this->emergency->isSafe()){
                echo "Helicopter keep fly!, no action since we have emergency Alarm!" . PHP_EOL;
                return;
            }

            if($event === UP){
                echo "Helicopter: Go Direct" . PHP_EOL;
            }else {
                echo "Helicopter: Helicopter Circle is empty!" . PHP_EOL;
            }
        }else if($sender instanceof Emergency){
            if($event === FIRE){
                echo "We have an Emergency case..." . PHP_EOL;
            }
        }else if($sender instanceof SafetyTeam){
            if($event === SAFE){
                $this->emergency->setIsSafe(true);
                echo "Its Safe now, you can complete your job!!" . PHP_EOL;
            }
        }
    }
}

// Client
$helicopter = new Helicopter();
$airbus = new Airbus();
$emergency = new Emergency();
$safetyTeam = new SafetyTeam();
$controlTower = new ControlTower($helicopter, $airbus, $emergency, $safetyTeam);

$helicopter->helicopterUP();
echo "============================================" . PHP_EOL;
$emergency->fireAlarm();
$helicopter->helicopterDOWN();
echo "============================================" . PHP_EOL;
$safetyTeam->safeAlarm();
$helicopter->helicopterDOWN();
echo "============================================" . PHP_EOL;
$emergency->fireAlarm();
echo "============================================" . PHP_EOL;
$airbus->airbusDOWN();
$airbus->airbusUP();
echo "============================================" . PHP_EOL;
$safetyTeam->safeAlarm();
$airbus->airbusDOWN();
$airbus->airbusUP();
$airbus->airbusDOWN();
