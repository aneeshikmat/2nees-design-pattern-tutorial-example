<?php
/**
 * This example just to simulate how Iterator can be work
 * 2nees.com
 */

class Countries implements \Iterator {
    private int $pointer = 0;
    private array $countriesList;

    /**
     * Countries constructor.
     * @param array $countriesList
     */
    public function __construct(array $countriesList)
    {
        $this->countriesList = $countriesList;
    }

    public function current()
    {
        echo "CURRENT VALUE" . PHP_EOL;
        return  $this->countriesList[$this->pointer];
    }

    public function next()
    {
        echo "NEXT VALUE" . PHP_EOL;
        $this->pointer += 1;
    }

    public function key()
    {
        echo "CURRENT KEY" . PHP_EOL;
        return $this->pointer;
    }

    public function valid()
    {
        echo "VALIDATE VALUE" . PHP_EOL;
        return isset($this->countriesList[$this->pointer]);
    }

    public function rewind()
    {
        echo "RESET VALUE" . PHP_EOL;
        $this->pointer = 0;
    }
}

$countriesList = new Countries([
    "Jordan", "Egypt", "Palestine", "Syria"
]);

foreach ($countriesList as $country){
    echo "^_^ => " . $country . PHP_EOL;
}