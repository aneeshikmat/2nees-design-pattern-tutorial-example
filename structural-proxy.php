<?php
/**
 * This example just to simulate how Proxy can be work
 * 2nees.com
 */

interface TextFilesControl {
    public function read(): void;
    public function write(string $txt): void;
    public function open(): void;
    public function close(): void;
    public function getFile();
}

class TxtFile implements TextFilesControl {
    private $file;
    private string $fileName;

    /**
     * txtFile constructor.
     * @param string $fileName
     */
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    public function read(): void
    {
        // Output one line until end-of-file
        echo file_get_contents("{$this->fileName}.txt") ?: "Empty" . PHP_EOL;
    }

    public function write(string $txt): void
    {
        fwrite($this->file, $txt);
    }

    public function open(): void
    {
        $this->file = fopen("{$this->fileName}.txt", "w+");
    }

    public function close(): void
    {
        fclose($this->file);
    }

    public function getFile()
    {
        return $this->file;
    }
}

class TxtFileProxy implements TextFilesControl {
    private TextFilesControl $textFilesControl;
    private int $role;

    /**
     * TxtFileProxy constructor.
     * @param TextFilesControl $textFilesControl
     */
    public function __construct(TextFilesControl $textFilesControl, int $role)
    {
        $this->textFilesControl = $textFilesControl;
        $this->role = $role;
    }

    public function read(): void
    {
        if($this->role !== 1){
            echo "User Has Read Access Start Reading" . PHP_EOL;
        }

        echo "Read Full Data: " . PHP_EOL;
        $this->textFilesControl->read();
    }

    public function write(string $txt): void
    {
        if($this->role === 1){
            $this->textFilesControl->write($txt);
        }else {
            echo "Proxy Prevent You from Write to File" . PHP_EOL;
        }
    }

    public function open(): void
    {
        if(!$this->getFile()){
            $this->textFilesControl->open();
            echo "File Open!" . PHP_EOL;
        }else {
            echo "File Already Open!" . PHP_EOL;
        }
    }

    public function close(): void
    {
        $this->textFilesControl->close();
        echo "File Closed!" . PHP_EOL;
    }

    public function getFile()
    {
        return $this->textFilesControl->getFile();
    }
}

$file1 = new TxtFile("2neesFile1");
$fileProxy1 = new TxtFileProxy($file1, 1);
$fileProxy1->open();
$fileProxy1->read();
$fileProxy1->write("2nees.com explain Proxy Pattern! - 1\n");
$fileProxy1->write("2nees.com explain Proxy Pattern! - 2\n");
$fileProxy1->read();
$fileProxy1->close();
echo "==================================================" . PHP_EOL;
$file2 = new TxtFile("2neesFile2");
$fileProxy2 = new TxtFileProxy($file2, 2);
$fileProxy2->open();
$fileProxy2->read();
$fileProxy2->write("2nees.com explain Proxy Pattern! - 1\n");
$fileProxy2->write("2nees.com explain Proxy Pattern! - 2\n");
$fileProxy2->read();
$fileProxy2->close();