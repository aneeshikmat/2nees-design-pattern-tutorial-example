<?php
/**
 * This example just to simulate how Bridge Combine with Builder
 * 2nees.com
 */

abstract class Message
{
    private string $message;
    protected MessageSender $messageSender;

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage($message): void
    {
        $this->message = $message;
    }

    public function setMessageSender(MessageSender $messageSender): void
    {
        $this->messageSender = $messageSender;
    }

    public abstract function sendMessage(): void;
}

class HtmlMessage extends Message
{
    public function sendMessage(): void
    {
        if (strip_tags($this->getMessage(), ['p', 'i', 'b']) !== $this->getMessage()) {
            echo "Html message Can accept only p, i, and b tags!!" . PHP_EOL;
            return;
        }

        $this->messageSender->send($this->getMessage());
    }
}

class PlanTextMessage extends Message
{
    public function sendMessage(): void
    {
        if (strip_tags($this->getMessage()) !== $this->getMessage()) {
            echo "Plan Text message cant accept Tags!" . PHP_EOL;
            return;
        }

        $this->messageSender->send($this->getMessage());
    }
}

interface MessageSender
{
    public function send($message): void;
}

class SMSMessage implements MessageSender
{
    public function send($message): void
    {
        echo "SMS Message: {$message} Sent!" . PHP_EOL;
    }
}

class EmailMessage implements MessageSender
{
    public function send($message): void
    {
        echo "to: 2nees.com; Body: {$message}; Subject: New Message From 2nees.com" . PHP_EOL;
    }
}

/**
 * This is a Director Class which its execute the building steps in particular sequence
 * 2nees.com
 */
class Director
{
    private Message $message;

    public function setBuilder(Message $message): void
    {
        $this->message = $message;
    }

    public function sendSMS()
    {
        $sms = new SMSMessage();
        // Send plan text message
        $this->message->setMessageSender($sms);
        $this->message->setMessage("Verification Code => 123456");
        $this->message->sendMessage();
    }

    public function sendEmail()
    {
        $email = new EmailMessage();
        $this->message->setMessageSender($email);
        $this->message->setMessage("<p><b>Verification Code</b> => <i>123456</i></p>");
        $this->message->sendMessage();
    }
}

$dir = new Director();
$dir->setBuilder(new PlanTextMessage());
$dir->sendSMS();
echo "==================================================" . PHP_EOL;
$dir->setBuilder(new HtmlMessage());
$dir->sendEmail();