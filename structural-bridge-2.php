<?php
/**
 * This example just to simulate how Bridge can be work
 * 2nees.com
 */

abstract class Message
{
    private string $message;
    protected MessageSender $messageSender;

    public function __construct(MessageSender $messageSender)
    {
        $this->setMessageSender($messageSender);
    }

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

// SMs message implementation
$sms = new SMSMessage();
// Email message implementation
$email = new EmailMessage();

// Send plan text message
$planMessage = new PlanTextMessage($sms);
// Valid Case
$planMessage->setMessage("Verification Code => 123456");
$planMessage->sendMessage();
// Wrong Case
$planMessage->setMessage("<p>2nees.com write this message to display error!</p>");
$planMessage->sendMessage();

echo "==================================================" . PHP_EOL;

// Send Message Contain Html Tag...
$htmlMessage = new HtmlMessage($email);
// Valid Case
$htmlMessage->setMessage("<p><b>Verification Code</b> => <i>123456</i></p>");
$htmlMessage->sendMessage();
// Wrong Case
$htmlMessage->setMessage("<div><b>Verification Code</b> => <i>123456</i></div>");
$htmlMessage->sendMessage();