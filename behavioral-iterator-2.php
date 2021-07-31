<?php
/**
 * This example just to simulate how Iterator can be work
 * 2nees.com
 */

class CustomIterableIgnoreSomeCountries implements \Iterator {
    private int $pointer;
    private int $startPoint;
    private array $countriesList;

    /**
     * Countries constructor.
     * @param array $countriesList
     */
    public function __construct(array $countriesList)
    {
        $this->startPoint = rand(0, 1);
        $this->countriesList = $countriesList;
    }

    public function current()
    {
        return $this->countriesList[$this->pointer];
    }

    public function next()
    {
        $this->pointer += 2;
    }

    public function key()
    {
        return $this->pointer;
    }

    public function valid()
    {
        return isset($this->countriesList[$this->pointer]);
    }

    public function rewind()
    {
        $this->pointer = $this->startPoint;
    }
}

class Countries implements \IteratorAggregate {
    private array $countriesList;

    /**
     * Countries constructor.
     * @param array $countriesList
     */
    public function __construct(array $countriesList)
    {
        $this->countriesList = $countriesList;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->countriesList);
    }

    public function getIteratorAsort(): ArrayIterator
    {
        $list = new ArrayIterator($this->countriesList);
        $list->asort();

        return $list;
    }

    public function customIterable(): CustomIterableIgnoreSomeCountries
    {
        return new CustomIterableIgnoreSomeCountries($this->countriesList);
    }
}

$countriesList = new Countries([
    "Jordan", "Egypt", "Palestine", "Syria"
]);

foreach ($countriesList as $country){
    echo "Default Iterable => " . $country . PHP_EOL;
}
echo "==============================================" . PHP_EOL;
foreach ($countriesList->getIteratorAsort() as $country){
    echo "Iterator ASORT => " . $country . PHP_EOL;
}
echo "==============================================" . PHP_EOL;
foreach ($countriesList->customIterable() as $country){
    echo "Custom Iterable => " . $country . PHP_EOL;
}