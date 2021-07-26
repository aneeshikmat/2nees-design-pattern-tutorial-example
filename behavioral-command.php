<?php
/**
 * This example just to simulate how Command can be work
 * 2nees.com
 */

/**
 * Interface TVControlCommand - This is the Command Interface which its contain all common command method
 * 2nees.com
 */
interface TVControlCommand {
    public function execute();
}

/**
 * Class TVBaseCommand - This is optional abstract class, I used it since this declaration common for all Concrete Command Class
 */
abstract class TVBaseCommand implements TVControlCommand {
    protected TVControl $control;

    /**
     * TVTurnOnControlCommand constructor.
     * @param TVControl $control
     */
    public function __construct(TVControl $control)
    {
        $this->control = $control;
    }
}

#region Concrete Command Classes which its used to execute specific work
class TVTurnOnControlCommand extends TVBaseCommand {
    public function execute()
    {
        if($this->control->isTvTurnStatus()){
            return;
        }

        $this->control->turnOn();
    }
}

class TVTurnOffControlCommand extends TVBaseCommand {
    public function execute()
    {
        if(!$this->control->isTvTurnStatus()){
            return;
        }

        $this->control->turnOff();
    }
}

class TVVolumeUpControlCommand extends TVBaseCommand {
    public function execute()
    {
        if(!$this->control->isTvTurnStatus()){
            echo "TV is OFF!, Turn TV ON to change volume UP!" . PHP_EOL;
            return;
        }

        $this->control->increaseVolume();
        echo "Your Volume: {$this->control->getVolume()}" . PHP_EOL;
    }
}

class TVVolumeDownControlCommand extends TVBaseCommand {
    public function execute()
    {
        if(!$this->control->isTvTurnStatus()){
            echo "TV is OFF!, Turn TV ON to change volume Down!" . PHP_EOL;
            return;
        }

        $this->control->decreaseVolume();
        echo "Your Volume: {$this->control->getVolume()}" . PHP_EOL;
    }
}
#endregion

/**
 * Class TVControl - Receiver Class, this class represent the real place for doing actual work
 */
class TVControl {
    private int $volume;
    private bool $tvTurnStatus;

    /**
     * TVControl constructor.
     * @param int $volume
     * @param bool $tvTurnStatus
     */
    public function __construct(int $volume, bool $tvTurnStatus)
    {
        $this->volume = $volume;
        $this->tvTurnStatus = $tvTurnStatus;
    }

    public function turnOff(): bool {
        $this->tvTurnStatus = false;
        echo "TV is turned OFF" . PHP_EOL;
        return $this->tvTurnStatus;
    }

    public function turnOn(): bool {
        $this->tvTurnStatus = true;
        echo "TV is turned ON" . PHP_EOL;
        return $this->tvTurnStatus;
    }

    public function decreaseVolume(): void
    {
        $volume = $this->volume - 1;
        if($volume >= 0){
            $this->volume = $volume;
        }
    }

    public function increaseVolume(): void
    {
        $volume = $this->volume + 1;
        if($volume <= 7){
            $this->volume = $volume;
        }
    }

    public function isTvTurnStatus(): bool
    {
        return $this->tvTurnStatus;
    }

    public function getVolume(): int
    {
        return $this->volume;
    }
}

/**
 * Class Application - Sender (Invoker) - This class for init request,
 * its can control more than one command, its trigger a command nested of send it directly
 *
 * Note: Maybe in our case here, is not needed, but usually its needed for keep history or collaborate and share it via component and so on...
 */
class Application {
    private TVTurnOffControlCommand $turnOffControlCommand;
    private TVTurnOnControlCommand $turnOnControlCommand;
    private TVVolumeUpControlCommand $volumeUpControlCommand;
    private TVVolumeDownControlCommand $volumeDownControlCommand;

    /**
     * Application constructor.
     * @param TVTurnOffControlCommand $turnOffControlCommand
     * @param TVTurnOnControlCommand $turnOnControlCommand
     * @param TVVolumeUpControlCommand $volumeUpControlCommand
     * @param TVVolumeDownControlCommand $volumeDownControlCommand
     */
    public function __construct(
        TVTurnOffControlCommand $turnOffControlCommand,
        TVTurnOnControlCommand $turnOnControlCommand,
        TVVolumeUpControlCommand $volumeUpControlCommand,
        TVVolumeDownControlCommand $volumeDownControlCommand
    ) {
        $this->turnOffControlCommand = $turnOffControlCommand;
        $this->turnOnControlCommand = $turnOnControlCommand;
        $this->volumeUpControlCommand = $volumeUpControlCommand;
        $this->volumeDownControlCommand = $volumeDownControlCommand;
    }

    public function on() {
        $this->turnOnControlCommand->execute();
    }

    public function off() {
        $this->turnOffControlCommand->execute();
    }

    public function up() {
        $this->volumeUpControlCommand->execute();
    }

    public function down() {
        $this->volumeDownControlCommand->execute();
    }

    public function doSomethingElseViaTheseCommand() {
        $this->on();
        $this->up();
        $this->up();
        $this->up();
    }

    // You can build history object to save history of execute to do some action....
}

// Client
$tv         = new TVControl(2, true);
$app        = new Application(
                    new TVTurnOffControlCommand($tv),
                    new TVTurnOnControlCommand($tv),
                    new TVVolumeUpControlCommand($tv),
                    new TVVolumeDownControlCommand($tv)
                );

/**
 * This loop to simulate we are press on remote or TV internal button, we share invoker as you see and can we use and put it any where...
 */
for ($i = 0; $i < 10; $i++){
    switch (rand(1, 8)):
        case 1:
        case 2:
            $app->on();
            break;
        case 3:
        case 4:
            $app->off();
            break;
        case 5:
        case 6:
            $app->up();
            break;
        case 7:
        case 8:
            $app->down();
            break;
    endswitch;
}
echo "==================================================" . PHP_EOL;
$app->doSomethingElseViaTheseCommand();