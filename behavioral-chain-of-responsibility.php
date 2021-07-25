<?php
/**
 * This example just to simulate how Chain of Responsibility can be work
 * 2nees.com
 */

/**
 * Interface OrderHandler - This is Handler
 * 2nees.com
 */
interface OrderHandler {
    public function setNextHandler(OrderHandler $orderHandler): OrderHandler;
    public function handle(Order $order);
}

/**
 * Class OrderBaseHandler - This is Base handler - Optional, but its useful to share common action for concrete handlers
 */
abstract class OrderBaseHandler implements OrderHandler {
    private ?OrderHandler $nextHandler = null;

    public function setNextHandler(OrderHandler $orderHandler): OrderHandler
    {
        $this->nextHandler = $orderHandler;

        return $this->nextHandler;
    }

    public function handle(Order $order)
    {
        if($this->nextHandler){
            return $this->nextHandler->handle($order);
        }

        return null;
    }
}

#region Concrete Handlers Class contain actual code for processes request
class CheckIsLoggedInUser extends OrderBaseHandler {
    /**
     * @throws Exception
     */
    public function handle(Order $order)
    {
        if($order->getUser()->getIsLoggedIn()){
            parent::handle($order);
        }else {
            throw new Exception("User not Logged In");
        }
    }
}

class CheckUserRole extends OrderBaseHandler {
    /**
     * @throws Exception
     */
    public function handle(Order $order)
    {
        if($order->getUser()->getRole() === 1){
            parent::handle($order);
        }else {
            throw new Exception("{$order->getUser()->getName()} Cant Access This Order!");
        }
    }
}

class CheckProductQuantity extends OrderBaseHandler {
    /**
     * @throws Exception
     */
    public function handle(Order $order)
    {
        $notAvailable = false;
        foreach ($order->getProducts() as $product){
            if($product->getQuantity() === 0){
                $notAvailable = $product;
            }
        }

        if($notAvailable === false){
            parent::handle($order);
        }else {
            throw new Exception("{$notAvailable->getName()} not available since its contain ({$notAvailable->getQuantity()})Item");
        }
    }
}

class AddLog extends OrderBaseHandler {
    /**
     * @throws Exception
     */
    public function handle(Order $order)
    {
        echo "Add Order Log for Order ID: #{$order->getOrderId()}" . PHP_EOL;
        parent::handle($order);
    }
}

class OrderSummery extends OrderBaseHandler {
    /**
     * @throws Exception
     */
    public function handle(Order $order)
    {
        $products = "";
        foreach ($order->getProducts() as $product){
            if($product->getQuantity() === 0){
                $products .= "#{$product->getId()} - {$product->getName()} - {$product->getQuantity()}" . PHP_EOL;
            }
        }

        echo "Order Owner: #{$order->getUser()->getId()} - {$order->getUser()->getName()}" . PHP_EOL;
        echo "Order Products: {$products}" . PHP_EOL;
        echo "Order Id: #{$order->getOrderId()}".PHP_EOL;
        parent::handle($order);
    }
}
#endregion

#region Services Class - Some of classes is used in our system for make any order
class Order {
    private Users $user;
    /**
     * @var Products[]
     */
    private array $products;
    private int $orderId;

    /**
     * Order constructor.
     * @param Users $user
     * @param Products[] $products
     * @param int $orderId
     */
    public function __construct(Users $user, array $products, int $orderId)
    {
        $this->user = $user;
        $this->products = $products;
        $this->orderId = $orderId;
    }

    /**
     * @return Users
     */
    public function getUser(): Users
    {
        return $this->user;
    }

    /**
     * @return Products[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @return int
     */
    public function getOrderId(): int
    {
        return $this->orderId;
    }
}

class Users {
    private bool $isLoggedIn;
    private string $name;
    private int $id;
    private int $role;

    /**
     * Users constructor.
     * @param string $name
     * @param int $id
     * @param int $role
     * @param bool $isLoggedIn
     */
    public function __construct(string $name, int $id, int $role, bool $isLoggedIn)
    {
        $this->name = $name;
        $this->id = $id;
        $this->role = $role;
        $this->isLoggedIn = $isLoggedIn;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getRole(): int
    {
        return $this->role;
    }

    /**
     * @return bool
     */
    public function getIsLoggedIn(): bool
    {
        return $this->isLoggedIn;
    }
}

class Products {
    private string $name;
    private int $id;
    private int $quantity;

    /**
     * Users constructor.
     * @param string $name
     * @param int $id
     */
    public function __construct(string $name, int $id, int $quantity)
    {
        $this->name = $name;
        $this->id = $id;
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
#endregion

// Init Data For Simulate User Request order
$user1 = new Users("Anees", 1, 1, true);
$user2 = new Users("Taher", 2, 1, true);
$user3 = new Users("Saed", 3, 2, true);
$user4 = new Users("Ibraheem", 4, 1, false);

$prod1 = new Products("Car", 1, 40);
$prod2 = new Products("Computer", 2, 10);
$prod3 = new Products("Gold Ring", 3, 0);

$order1 = new Order($user1, [$prod1, $prod2], 1);
$order2 = new Order($user2, [$prod3], 2);
$order3 = new Order($user3, [$prod1], 3);
$order4 = new Order($user4, [$prod1, $prod2], 4);

// Here is the important part for client, we create instance from all concrete handlers needed to execute base on our business logic
$checkIsLoggedInUser    = new CheckIsLoggedInUser();
$checkUserRole          = new CheckUserRole();
$checkProductQuantity   = new CheckProductQuantity();
$AddLog                 = new AddLog();
$orderSummery           = new OrderSummery();

// Set order of handler execute
$checkIsLoggedInUser
    ->setNextHandler($checkUserRole)
    ->setNextHandler($checkProductQuantity)
    ->setNextHandler($AddLog)
    ->setNextHandler($orderSummery);

try {
    $checkIsLoggedInUser->handle($order1);
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
echo "==================================================" . PHP_EOL;
try {
    $checkIsLoggedInUser->handle($order2);
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
echo "==================================================" . PHP_EOL;
try {
    $checkIsLoggedInUser->handle($order3);
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
echo "==================================================" . PHP_EOL;
try {
    $checkIsLoggedInUser->handle($order4);
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}