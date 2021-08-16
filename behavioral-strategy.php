<?php
/**
 * This example just to simulate how Strategy can be work
 * 2nees.com
 */

interface EncryptStrategy {
    public function encrypt($text): string;
}

class EncryptedContext {
    private EncryptStrategy $encryptStrategy;
    private string $text;

    /**
     * EncryptedContext constructor.
     * @param EncryptStrategy $encryptStrategy
     */
    public function __construct(EncryptStrategy $encryptStrategy)
    {
        $this->encryptStrategy = $encryptStrategy;
    }

    public function encryptText(): void {
        echo $this->encryptStrategy->encrypt($this->text) . PHP_EOL;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @param EncryptStrategy $encryptStrategy
     */
    public function setEncryptStrategy(EncryptStrategy $encryptStrategy): void
    {
        $this->encryptStrategy = $encryptStrategy;
    }
}

class MD5Encrypt implements EncryptStrategy {
    public function encrypt($text): string
    {
        return "MD5: " . md5($text);
    }
}

class SHA1Encrypt implements EncryptStrategy {
    public function encrypt($text): string
    {
        return "SHA1: " . sha1($text);
    }
}

class Haval160Encrypt implements EncryptStrategy {
    public function encrypt($text): string
    {
        return "haval160,4: " . hash("haval160,4", $text);
    }
}

$encrypt = new EncryptedContext(new SHA1Encrypt());
$encrypt->setText("2nees.com");
$encrypt->encryptText();
$encrypt->setEncryptStrategy(new MD5Encrypt());
$encrypt->encryptText();
$encrypt->setEncryptStrategy(new Haval160Encrypt());
$encrypt->encryptText();