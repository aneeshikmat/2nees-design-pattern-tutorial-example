<?php
/**
 * This example just to simulate how Aggregation can be work
 * 2nees.com
 */

class Collage {
    private string $name;
    /**
     * @var Teacher[]
     */
    private array $teachers;
    /**
     * @var Student[]
     */
    private array $students;

    /**
     * Collage constructor.
     * @param string $name
     * @param array $teachers
     * @param array $students
     */
    public function __construct(string $name, array $teachers, array $students)
    {
        $this->name = $name;
        $this->teachers = $teachers;
        $this->students = $students;
    }

    public function printCollageDetails(): void
    {
        $teachersList = [];
        $studentsList = [];

        foreach ($this->teachers as $teacher){
            $teachersList[] = $teacher->getName();
        }

        foreach ($this->students as $student){
            $studentsList[] = $student->getName();
        }

        $printableTeachers = implode(", ", $teachersList);
        $printableStudents = implode(", ", $studentsList);

        echo "
            Collage Name: {$this->name}
            Teachers: {$printableTeachers}
            Students: {$printableStudents}" . PHP_EOL;
    }
}

abstract class Users {
    private string $name;

    /**
     * Users constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}

class Teacher extends Users {
    // Do any thing...
}

class Student extends Users {
    // Do any thing...
}

// Client
$tech1 = new Teacher("Hikmat");
$tech2 = new Teacher("Ahmad");

$std1 = new Student("Anees");
$std2 = new Student("Taher");
$std3 = new Student("Saed");

$collage = new Collage("Information Technology", [$tech1, $tech2], [$std1, $std2, $std3]);
$collage->printCollageDetails();
unset($collage);
echo $tech1->getName() . PHP_EOL;
echo $std1->getName();