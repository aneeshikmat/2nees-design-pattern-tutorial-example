<?php

class EmployeesPrototype
{
    private string $name;
    private int $age;
    private Salary $salary;
    private PrivilegesTypes $privilegesTypes;

    /**
     * EmployeesPrototype constructor.
     * @param string $name
     * @param int $age
     * @param Salary $salary
     * @param PrivilegesTypes $privilegesTypes
     */
    public function __construct(string $name, int $age, Salary $salary, PrivilegesTypes $privilegesTypes)
    {
        $this->name = $name;
        $this->age = $age;
        $this->salary = $salary;
        $this->privilegesTypes = $privilegesTypes;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param int $age
     */
    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    public function __clone()
    {
        $this->setAge(18);

        $this->salary->setSalary(300);
        $this->salary->setTax(0.1);

        $this->privilegesTypes->clearPrivilegesTypes();
        $this->privilegesTypes->addPrivilegesTypes("view");

        return $this;
    }
}

class Salary {
    private float $salary;
    private float $tax;

    /**
     * @param float $salary
     */
    public function setSalary(float $salary): void
    {
        $this->salary = $salary;
    }

    /**
     * @param float $tax
     */
    public function setTax(float $tax): void
    {
        $this->tax = $tax;
    }
}

class PrivilegesTypes {
    private array $privilegesTypes;

    /**
     * @return array
     */
    public function clearPrivilegesTypes(): void
    {
        $this->privilegesTypes = [];
    }

    /**
     * @param string $privilegesType
     */
    public function addPrivilegesTypes(string $privilegesType): void
    {
        $this->privilegesTypes[] = $privilegesType;
    }
}

// Client Code
$salary = new Salary();
$salary->setSalary(500);
$salary->setTax(0.16);

$pr = new PrivilegesTypes();
$pr->addPrivilegesTypes("add");
$pr->addPrivilegesTypes("update");

$newEmployee = new EmployeesPrototype("Anees", 29, $salary, $pr);
echo PHP_EOL . "=============== Print New Employee Data ==========================" . PHP_EOL;
print_r($newEmployee);
echo PHP_EOL . "=============== Print Cloned Employee Data ==========================" . PHP_EOL;
$clonedEmployee = clone $newEmployee;
$clonedEmployee->setName("Hikmat");
print_r($clonedEmployee);