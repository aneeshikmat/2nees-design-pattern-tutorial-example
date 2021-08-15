<?php
/**
 * This example just to simulate how State can be work
 * 2nees.com
 */

const CREATE_STATE = "CREATE";
const CANCELED_STATE = "CANCELED";
const PAYMENT_STATE = "PAYMENT";
const PAYMENT_PROCEED_STATE = "PAYMENT_PROCEED";
const DONE_STATE = "DONE";
const FAIL_STATE = "FAIL";
const SUCCESS_STATE = "SUCCESS";

/**
 * Class MyStore - this is the Context which its defines the reference to an instance of a State subclass
 */
class MyStore {
    private State $state;// This is the reference state
    private string $processStatus;// Normal variable to I do some logic...

    /**
     * MyStore constructor.
     */
    public function __construct()
    {
        $this->transitionTo(new CreateState());
    }

    /**
     * Allows changing the State object at runtime.
     */
    public function transitionTo(State $state): void
    {
        $this->state = $state;
        $this->state->setMyStore($this);
    }

    /**
     * Delegate Work to current state object
     */
    public function nextStep(): void
    {
        $this->state->nextStep();
    }

    /**
     * @return string
     */
    public function getProcessStatus(): string
    {
        return $this->processStatus;
    }

    /**
     * @param string $processStatus
     */
    public function setProcessStatus(string $processStatus): void
    {
        $this->processStatus = $processStatus;
    }
}

/**
 * Class State - State interface - declares methods that all Concrete State should
 * implement and also provides a backreference to the Context object
 */
abstract class State {
    protected MyStore $myStore;

    public function setMyStore(MyStore $myStore)
    {
        $this->myStore = $myStore;
    }

    abstract public function nextStep(): void;
}

#region ConcreteState - implement various behaviors, associated with a state of the Context
class CreateState extends State {
    public function nextStep(): void
    {
        echo "CREATED STEP!" . PHP_EOL;
        $this->myStore->setProcessStatus(CREATE_STATE);
        $this->myStore->transitionTo(new PaymentState());
    }
}

class PaymentState extends State {
    public function nextStep(): void
    {
        echo "PAYMENT STEP!" . PHP_EOL;
        $this->myStore->setProcessStatus(PAYMENT_STATE);
        if(rand(0, 5) > 2){
            $this->myStore->transitionTo(new PaymentProceedState());
        }else {
            $this->myStore->transitionTo(new CanceledState());
        }
    }
}

class PaymentProceedState extends State {
    public function nextStep(): void
    {
        echo "PAYMENT PROCEED STEP!" . PHP_EOL;
        $this->myStore->setProcessStatus(PAYMENT_PROCEED_STATE);

        if(rand(0, 5) > 2){
            $this->myStore->transitionTo(new SuccessState());
        }else {
            $this->myStore->transitionTo(new FailState());
        }
    }
}

class FailState extends State {
    public function nextStep(): void
    {
        echo "FAIL STEP!" . PHP_EOL;
        $this->myStore->setProcessStatus(FAIL_STATE);
        $this->myStore->transitionTo(new DoneState());
    }
}

class SuccessState extends State {
    public function nextStep(): void
    {
        echo "SUCCESS STEP!" . PHP_EOL;
        $this->myStore->setProcessStatus(SUCCESS_STATE);
        $this->myStore->transitionTo(new DoneState());
    }
}

class CanceledState extends State {
    public function nextStep(): void
    {
        echo "CANCELED STEP!" . PHP_EOL;
        $this->myStore->setProcessStatus(CANCELED_STATE);
        $this->myStore->transitionTo(new DoneState());
    }
}

class DoneState extends State {
    public function nextStep(): void
    {
        if($this->myStore->getProcessStatus() === DONE_STATE){
            return;
        }

        echo "DONE STEP!" . PHP_EOL;
        $this->myStore->setProcessStatus(DONE_STATE);
        // No need to do any transition, since its last step...
    }
}
#endregion

// Client....
for ($i = 1; $i <= 5; $i++){
    $myStore = new MyStore();
    $myStore->nextStep();
    $myStore->nextStep();
    $myStore->nextStep();
    $myStore->nextStep();
    $myStore->nextStep();
    echo "===================================" . PHP_EOL;
}