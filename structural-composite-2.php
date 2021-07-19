<?php
/**
 * This example just to simulate how Composite can be work
 * 2nees.com
 */

/**
 * Common Operations For all objects
 * Interface Family
 */
interface Family {
    public function render(): array;
}

/**
 * Leaf class
 * Class FamilyMembers
 */
class FamilyMembers implements Family {
    private int $id;
    private string $name;
    private Address $address;
    private MemberDetails $memberDetails;
    private ?array $children = [];

    /**
     * FamilyMembers constructor.
     * @param int $id
     * @param string $name
     * @param Address $address
     * @param MemberDetails $memberDetails
     */
    public function __construct(int $id, string $name, Address $address, MemberDetails $memberDetails)
    {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->memberDetails = $memberDetails;
    }

    /**
     * @param Family $children
     */
    public function addChildren(Family $children): void
    {
        array_push($this->children, $children);
    }

    /**
     * @return array
     */
    public function render(): array
    {
        return [
            "Id" => $this->id,
            "Name" => $this->name,
            "Children" => array_map(fn (Family $children) => $children->render(), $this->children),
            "Address" => $this->address->render(),
            "Details" => $this->memberDetails->render()
        ];
    }
}

class MemberDetails implements Family {
    private string $gender;
    private int $age;
    private Job $job;

    /**
     * MemberDetails constructor.
     * @param string $gender
     * @param int $age
     */
    public function __construct(string $gender, int $age)
    {
        $this->gender = $gender;
        $this->age = $age;
        $this->job = new Job();
    }

    /**
     * @return array
     */
    public function render(): array
    {
        return [
            "Gender" => $this->gender,
            "Age" => $this->age,
            "Job" => $this->job->render()
        ];
    }
}

class Job implements Family {
    const JOBS = [
        "Programmer",
        "Accountant",
        "Doctor",
        "Engineer"
    ];

    public function render(): array
    {
        return [
            "jobTitle" => self::JOBS[rand(0, 3)],
            "salary" => rand(200, 3000)
        ];
    }
}

class Address implements Family {
    private string $streetName;
    private string $city;

    /**
     * Address constructor.
     * @param string $streetName
     * @param string $city
     */
    public function __construct(string $streetName, string $city)
    {
        $this->streetName = $streetName;
        $this->city = $city;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        return [
            "street" => $this->streetName,
            "city" => $this->city
        ];
    }
}

class Composite implements Family {
    private SplObjectStorage $members;

    /**
     * Composite constructor.
     */
    public function __construct()
    {
        $this->members = new \SplObjectStorage();
    }

    /**
     * @param Family $member
     */
    public function addMember(Family $member){
        $this->members->attach($member);
    }

    /**
     * @param Family $member
     */
    public function removeMember(Family $member){
        $this->members->detach($member);
    }

    /**
     * @return array
     */
    public function render(): array
    {
        $result = [];
        foreach ($this->members as $member){
            array_push($result, $member->render());
        }

        return $result;
    }
}

// Simulate Client Data
$aneesParent    = new FamilyMembers(
    1,
    "Anees",
    new Address("st. zero", "Zarqa"),
    new MemberDetails("Male", 95)
);

$rasheedParent    = new FamilyMembers(
    2,
    "Rasheed",
    new Address("st. one", "Mafraq"),
    new MemberDetails("Male", 90)
);

$hikmat   = new FamilyMembers(
    3,
    "Hikmat",
    new Address("st. two", "East Zarqa"),
    new MemberDetails("Male", 55)
);

$aneesChild   = new FamilyMembers(
    4,
    "Anees",
    new Address("st. three", "West Zarqa"),
    new MemberDetails("Male", 30)
);

$taher   = new FamilyMembers(
    5,
    "Taher",
    new Address("st. four", "Amman"),
    new MemberDetails("Male", 29)
);

$saed   = new FamilyMembers(
    5,
    "Saed",
    new Address("st. five", "Aqaba"),
    new MemberDetails("Male", 28)
);

// Prepare Data For Composite
$aneesParent->addChildren($hikmat);
$hikmat->addChildren($aneesChild);
$hikmat->addChildren($taher);
// Prepare Data For Composite
$rasheedParent->addChildren($saed);

// You can add as you want from members(leaf's), or create other composite to retrieve data or details as you want, here all data return
$composite = new Composite();
$composite->addMember($aneesParent);
$composite->addMember($rasheedParent);
print_r($composite->render());

echo "==================================================" . PHP_EOL;


// You can create a new composite simply and add own leaf!
$composite1 = new Composite();
$composite1->addMember($aneesChild);
$composite1->addMember($taher);
$composite1->addMember($saed);
print_r($composite1->render());


echo "==================================================" . PHP_EOL;


// You can create a new composite simply and add own leaf!
$composite2 = new Composite();
$composite2->addMember($rasheedParent);
print_r($composite2->render());

echo "==================================================" . PHP_EOL;

// If you like to create tree from more than one Composite (Combine it), you can do that simply...and any time
$composite3 = new Composite();
$composite3->addMember($composite);
$composite3->addMember($composite2);
$composite3->addMember($composite1);
$composite3->addMember(new FamilyMembers(
    6,
    "Additional Leaf!",
    new Address("st. six", "Zarqa Center"),
    new MemberDetails("Male", 52)
));// And you can add any time any leaf!
print_r($composite3->render());