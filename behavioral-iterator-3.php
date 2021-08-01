<?php
/**
 * This example just to simulate how Iterator can be work With Collection or Pass Iterator
 * 2nees.com
 */

interface ItemsCollection {
    public function oddItem(): OddIndex;
    public function evenItem(): EvenIndex;
}

class Countries implements ItemsCollection {
    private array $countriesList;

    /**
     * Countries constructor.
     * @param array $countriesList
     */
    public function __construct(array $countriesList)
    {
        $this->countriesList = $countriesList;
    }

    public function oddItem(): OddIndex
    {
        return new OddIndex($this->countriesList);
    }

    public function evenItem(): EvenIndex
    {
        return new EvenIndex($this->countriesList);
    }
}

class Countires2 {
    public function doMyIterate(\Iterator $iterator)
    {
        foreach ($iterator as $country){
            echo "Pass Iterator and Dome Same Job => " . $country . PHP_EOL;
        }
    }
}


class OddIndex implements \Iterator {
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
        return  $this->countriesList[$this->pointer];
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
        $this->pointer = 1;
    }
}

class EvenIndex implements \Iterator {
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
        return  $this->countriesList[$this->pointer];
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
        $this->pointer = 0;
    }
}

$list = [
    "Jordan", "Egypt", "Palestine", "Syria"
];
$countriesList = new Countries($list);

foreach ($countriesList->oddItem() as $country){
    echo "Default Iterable => " . $country . PHP_EOL;
}
echo "==============================================" . PHP_EOL;
foreach ($countriesList->evenItem() as $country){
    echo "Default Iterable => " . $country . PHP_EOL;
}

echo "==============================================" . PHP_EOL;
$countriesList = new Countires2();
$countriesList->doMyIterate(new OddIndex($list));
echo "==============================================" . PHP_EOL;
$countriesList->doMyIterate(new EvenIndex($list));
