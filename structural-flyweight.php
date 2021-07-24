<?php
/**
 * This example just to simulate how Flyweight can be work
 * 2nees.com
 */

/**
 * Class TreeType - This flyweight class which its contain a unique state
 ** When you look to this simulate its a big size in KB nested of B
 */
class TreeType {
    const TREE_NAME_LIST = [
        "Apple",
        "Orange",
        "Banana"
    ];

    const TREE_SIZE_LIST = [
        "10x10",
        "10x15",
        "20x20"
    ];

    private string $name;
    private string $size;
    private string $image;

    /**
     * TreeType constructor.
     * @param string $name
     * @param string $size
     * @param string $image
     */
    public function __construct(string $name, string $size, string $image)
    {
        $this->name     = $name;
        $this->size     = $size;
        $this->image    = $image;
    }

    public function draw(): string {
        return "name: {$this->name}, size: {$this->size}, img: {$this->image}";
    }
}

/**
 * Class TreeFactory - Flyweight factory which its used to create a flyweight object or re-use exists one
 */
class TreeFactory {
    /**
     * @var TreeType[]
     */
    private static array $treeTypes = [];

    public static function getTreeType(string $name, string $size, string $image): TreeType
    {
        $key = md5($name.$size.$image);
        if(!array_key_exists($key, self::$treeTypes)){
            self::$treeTypes[$key] = new TreeType($name, $size, $image);
        }

        return self::$treeTypes[$key];
    }

    public static function getAllTreesType(): void {
        echo "Total Count For Unique Object Saved on RAM: " . count(self::$treeTypes) . PHP_EOL;
        print_r(array_map(fn(TreeType $treeType) => $treeType->draw(), self::$treeTypes));
    }
}

/**
 * Class Tree - This is context which its create contextual Object, and you can create a lot and alot of these object
 ** and you will be safe since its too small...
 */
class Tree {
    private int $x;
    private int $y;
    private TreeType $treeType;

    /**
     * Tree constructor.
     * @param int $x
     * @param int $y
     * @param TreeType $treeType
     */
    public function __construct(int $x, int $y, TreeType $treeType)
    {
        $this->x = $x;
        $this->y = $y;
        $this->treeType = $treeType;
    }

    public function draw(): string {
        return  "x: {$this->x}, y: {$this->y}, {$this->treeType->draw()}}";
    }
}

/**
 * Class GameLand - This is the Flyweight for client, which its return or represent data for client
 */
class GameLand {
    /**
     * @var Tree[]
     */
    private array $trees;

    /**
     * @return array of Drawing data
     */
    public function draw(): array
    {
        return array_map(fn(Tree $tree) =>
        $tree->draw()
            , $this->trees);
    }

    /**
     * @param $x
     * @param $y
     * @param $name
     * @param $size
     */
    public function addTree($x, $y, $name, $size, $image): void
    {
        $treeType = TreeFactory::getTreeType($name, $size, $image);
        $this->trees[] = new Tree($x, $y, $treeType);
    }
}

// Client Code
$land = new GameLand();
// Simulate to add 100K of trees
for($i = 0; $i < 100000; $i++){
    $land->addTree($i, $i * 2, TreeType::TREE_NAME_LIST[rand(0, 2)], TreeType::TREE_SIZE_LIST[rand(0, 2)], md5(rand(0, 2)));
}

// Call Draw Land
print_r($land->draw());
echo "==================================================" . PHP_EOL;
// Here get Total Count For Unique Object Saved on RAM (To validate our work)
TreeFactory::getAllTreesType();