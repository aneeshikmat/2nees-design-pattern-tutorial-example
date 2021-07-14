<?php
/**
 * This example just to simulate how adapter can be work
 * 2nees.com
 */

/**
 * Class SMSClient
 */
abstract class SMSClient
{
    private string $number;
    private string $message;

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    abstract public function sendMessage();
}

class OldSMSCompany extends SMSClient
{
    public function sendMessage()
    {
        echo "SMS Message Sent to {$this->getNumber()}: {$this->getMessage()}!" . PHP_EOL;
    }
}

class NewSMSCompanyAdaptee
{
    private string $connect;
    private string $oath;
    private string $num;
    private string $text;

    public function __construct(bool $connect, string $oath, string $num, string $text)
    {
        $this->connect = $connect ? "connect" : "disconnect";
        $this->oath = $oath;
        $this->num = $num;
        $this->text = $text;
    }

    public function send()
    {
        if($this->isConnected()){
            $rand = rand(1, 50);
            echo "SMS Sent #{$rand}:  {$this->num}: {$this->text}!". PHP_EOL;
        }else {
            echo "SMS NOT SEND: {$this->connect}:{$this->oath}". PHP_EOL;
        }
    }

    private function isConnected(): bool
    {
        return $this->oath === "2nees.com" && $this->connect;
    }
}

class NewSMSCompanyAdapter extends SMSClient {
    public function sendMessage()
    {
        $textRandom  = ["2nees.com", "Anees"][rand(0, 1)];
        $randConnect = rand(0, 5);
        $newSmsCompanyAdaptee = new NewSMSCompanyAdaptee($randConnect > 1, $textRandom, $this->getNumber(), $this->getMessage());
        $newSmsCompanyAdaptee->send();
    }
}

// Company changed? Just update company class ^^, and keep your client code in loop as is.
$sms = new OldSMSCompany();
//$sms = new NewSMSCompanyAdapter();
for ($i = 1; $i <= 5; $i++){
    $sms->setNumber("00962000{$i}");
    $sms->setMessage("SMS send from 2nees.com+{$i}");
    $sms->sendMessage();
}