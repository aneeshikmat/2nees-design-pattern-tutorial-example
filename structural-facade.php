<?php
/**
 * This example just to simulate how Facade can be work
 * 2nees.com
 */

/**
 * Class SocialMediaFacade - Facade Class
 */
class SocialMediaFacade {
    protected Facebook $facebook;
    protected Twitter $twitter;
    protected LinkedIn $linkedIn;

    /**
     * SocialMediaFacade constructor.
     * @param Facebook $facebook
     * @param Twitter $twitter
     * @param LinkedIn $linkedIn
     */
    public function __construct(Facebook $facebook, Twitter $twitter, LinkedIn $linkedIn)
    {
        $this->facebook = $facebook;
        $this->twitter = $twitter;
        $this->linkedIn = $linkedIn;
    }

    /**
     * Set Messages
     * @param $message
     */
    public function setMessage($message): void {
        $this->facebook->setMessage($message);
        $this->twitter->setMessage($message);
        $this->linkedIn->setMessage($message);
    }

    /**
     * Share messages for all providers
     */
    public function share(): void {
        $this->facebook->share();
        $this->twitter->share();
        $this->linkedIn->share();
    }
}

#region Simulate Complex Subsystem
abstract class SocialMedia {
    protected string $message;

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    abstract public function share(): void;
}

class Facebook extends SocialMedia {
    public function share(): void
    {
        echo "Facebook Shared this message: {$this->message}" . PHP_EOL;
    }
}

class Twitter extends SocialMedia {
    public function share(): void
    {
        echo "Twitter Shared this message: {$this->message}" . PHP_EOL;
    }
}

class LinkedIn extends SocialMedia {
    public function share(): void
    {
        echo "LinkedIn Shared this message: {$this->message}" . PHP_EOL;
    }
}
#endregion

// Client Script
$message = "2nees.com Facade Design Pattern.";

// Old Way (Before use Facade)
$facebook = new Facebook();
$facebook->setMessage($message);
$facebook->share();

$twitter  = new Twitter();
$twitter->setMessage($message);
$twitter->share();

$linkedIn = new LinkedIn();
$linkedIn->setMessage($message);
$linkedIn->share();

echo "==================================================" . PHP_EOL;
// New Way (After use Facade)
$facade = new SocialMediaFacade(new Facebook(), new Twitter(), new LinkedIn());
$facade->setMessage("{$message} (We Are Using Facade Now)");
$facade->share();