<?php
/**
 * This example just to simulate how Visitor can be work
 * 2nees.com
 */

/**
 * Interface InsuranceContracts - Element Interface - Its used to declare accepting Method for Visitor,
 ** and you can additional method if you want...
 */
interface InsuranceContracts {
    public function accept(Visitor $visitor): string;// The main method
    public function getCost(): int;
    public function getContracts(): string;
}

#region Concrete Element - were we implement the acceptance method
class HomeInsurance implements InsuranceContracts {
    public function getCost(): int
    {
        return rand(200, 500);
    }

    public function getContracts(): string
    {
        return "Your Home Insurance Contracts Is Approved for 1 year: {$this->getCost()}";
    }

    public function accept(Visitor $visitor): string
    {
        return "Home Insurance: " . $visitor->visitHome($this);
    }
}

class CarInsurance implements InsuranceContracts {
    private int $engineSize;

    /**
     * CarInsurance constructor.
     */
    public function __construct()
    {
        $this->engineSize = 1;
    }

    public function getCost(): int
    {
        return 100 * $this->getEngineSize();
    }

    /**
     * @return int
     */
    public function getEngineSize(): int
    {
        return $this->engineSize;
    }

    /**
     * @param int $engineSize
     */
    public function setEngineSize(int $engineSize): void
    {
        $this->engineSize = $engineSize;
    }

    public function getContracts(): string
    {
        return "Your Car Insurance Contracts Is Approved for 1 year: {$this->getCost()}
         And Your Engine is: {$this->getEngineSize()}";
    }

    public function accept(Visitor $visitor): string
    {
        return "Car Insurance: " . $visitor->visitCar($this);
    }
}

class BankInsurance implements InsuranceContracts {
    private int $numberOfEmployee;
    private int $cost;

    /**
     * BankInsurance constructor.
     */
    public function __construct()
    {
        $this->numberOfEmployee = 50;
        $this->cost = 250;
    }

    public function getCost(): int
    {
        return $this->cost * $this->numberOfEmployee;
    }

    /**
     * @param $cost
     */
    public function setCost($cost): void{
        $this->cost = $cost;
    }

    public function getContracts(): string
    {
        return "
                ******************
             Bank Contracts:
             Total Cost: {$this->getCost()}
             Number OF Employee: {$this->numberOfEmployee}
             And Cost For every one is: {$this->cost}
                ******************
         ";
    }

    public function accept(Visitor $visitor): string
    {
        return "Bank Insurance: " . $visitor->visitBank($this);
    }
}

class HospitalInsurance implements InsuranceContracts {
    private int $numberOfRoom;
    private int $numberOfBeds;

    /**
     * HospitalInsurance constructor.
     */
    public function __construct()
    {
        $this->numberOfRoom = 20;
        $this->numberOfBeds = 50;
    }

    public function getCost(): int
    {
        return 1000 * $this->numberOfBeds * $this->numberOfRoom;
    }

    public function getContracts(): string
    {
        return "
                ******************
                 Hospital Contracts:
                 Total Cost: {$this->getCost()}
                ******************
         ";
    }

    public function accept(Visitor $visitor): string
    {
        return "Hospital Insurance: " . $visitor->visitHospital($this);
    }

    /**
     * @return int
     */
    public function getNumberOfRoom(): int
    {
        return $this->numberOfRoom;
    }

    /**
     * @return int
     */
    public function getNumberOfBeds(): int
    {
        return $this->numberOfBeds;
    }

    /**
     * @param int $numberOfRoom
     */
    public function setNumberOfRoom(int $numberOfRoom): void
    {
        $this->numberOfRoom = $numberOfRoom;
    }

    /**
     * @param int $numberOfBeds
     */
    public function setNumberOfBeds(int $numberOfBeds): void
    {
        $this->numberOfBeds = $numberOfBeds;
    }
}
#endregion

/**
 * Interface Visitor -set of visiting methods that can take concrete elements of an object structure
 */
interface Visitor {
    public function visitHome(HomeInsurance $homeInsurance);
    public function visitCar(CarInsurance $carInsurance);
    public function visitBank(BankInsurance $bankInsurance);
    public function visitHospital(HospitalInsurance $hospitalInsurance);
}

/**
 * Class CovidCondition - Concrete Visitor - Here we Implement several versions of the same behaviors set in Visitors interface
 ** Note: we can add Concrete Visitor As we need...
 */
class CovidCondition implements Visitor {
    private bool $includeCovid;

    /**
     * CovidCondition constructor.
     * @param bool $includeCovid
     */
    public function __construct(bool $includeCovid)
    {
        $this->includeCovid = $includeCovid;
    }

    public function visitHome(HomeInsurance $homeInsurance)
    {
        echo "You are in Covid Visitor Conditions, and your contract cant contain Covid!" . PHP_EOL;
        echo $homeInsurance->getContracts() . PHP_EOL;
    }

    public function visitCar(CarInsurance  $carInsurance)
    {
        echo "You are in Covid Visitor Conditions, and your contract cant contain Covid!" . PHP_EOL;
        echo $carInsurance->getContracts() . PHP_EOL;
    }

    public function visitBank(BankInsurance $bankInsurance)
    {
        if($this->includeCovid){
            echo "You are in Covid Visitor Conditions, and your company pay to include Covid in your Insurance!" . PHP_EOL;
            $bankInsurance->setCost(125);
        }else {
            echo "You are in Covid Visitor Conditions, and your company not pay to include this type of disease!" . PHP_EOL;
        }

        echo $bankInsurance->getContracts() . PHP_EOL;
    }

    public function visitHospital(HospitalInsurance $hospitalInsurance)
    {
        if($hospitalInsurance->getNumberOfRoom() < 25){
            echo "Sorry, After Covid small hospital not supported!" . PHP_EOL;
            return;
        }

        if($this->includeCovid){
            echo "You are in Covid Visitor Conditions, and your company pay to include Covid in your Insurance!" . PHP_EOL;
            $contracts = $hospitalInsurance->getContracts();
            $contracts .= "Number of beds Support: {$hospitalInsurance->getNumberOfBeds()}" . PHP_EOL;
            $contracts .= "Number of Room Support: {$hospitalInsurance->getNumberOfRoom()}" . PHP_EOL;
            echo $contracts . PHP_EOL;
        }else {
            echo "Sorry, Your Hospital Must be include Covid Addon For its insurance Contract!!" . PHP_EOL;
        }
    }
}

// Clint
$homeInsurance = new HomeInsurance();
$carInsurance = new CarInsurance();
$bankInsurance = new BankInsurance();
$hospitalInsurance = new HospitalInsurance();

// Test Scenario 1
$covid1 = new CovidCondition(true);
$homeInsurance->accept($covid1);
$carInsurance->accept($covid1);
$bankInsurance->accept($covid1);
$hospitalInsurance->accept($covid1);
echo "====================End 1===========================" . PHP_EOL;

// Test Scenario 2
$covid2 = new CovidCondition(false);
$homeInsurance->accept($covid2);
$carInsurance->accept($covid2);
$bankInsurance->accept($covid2);
$hospitalInsurance->accept($covid2);
echo "====================End 2===========================" . PHP_EOL;

// Test Scenario 3
$covid3 = new CovidCondition(true);
$homeInsurance->accept($covid3);
$carInsurance->setEngineSize(20);
$carInsurance->accept($covid3);
$bankInsurance->accept($covid3);
$hospitalInsurance->setNumberOfRoom(50);
$hospitalInsurance->setNumberOfBeds(100);
$hospitalInsurance->accept($covid3);
echo "====================End 3===========================" . PHP_EOL;