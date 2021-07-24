<?php
/**
 * This example just to simulate how Proxy can be work
 * The main idea for this example just to explains you can manage or enhancement your client code via
 ** adding builder director or create class for factory our job or implement any pattern or idea you want...
 * 2nees.com
 */

/**
 * Interface TextFilesControl - The interface for original service and proxy
 */
interface TextFilesControl {
    public function read(): void;
    public function write(string $txt): void;
    public function open(): void;
    public function close(): void;
    public function getFile();
}

/**
 * Class TxtFile - Original Server which its a concrete implementation
 */
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

/**
 * Class TxtFileProxy - This is the Proxy class which implements the same interface for Original Class
 */
class TxtFileProxy implements TextFilesControl {
    private TextFilesControl $textFilesControl;
    private int $role;

    /**
     * TxtFileProxy constructor.
     * @param TextFilesControl $textFilesControl
     * @param int $role
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
            echo "Writing..." . PHP_EOL;
            $this->textFilesControl->write($txt);
        }else {
            echo "Proxy Prevent You from Write to File" . PHP_EOL;
        }
    }

    public function open(): void
    {
        if(!$this->getFile()){
            $this->textFilesControl->open();
            echo "File Open OR Created!" . PHP_EOL;
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

/**
 * Class TxtFileFactory - Idea to create factory with static method to do the same steps for my example...
 */
class TxtFileFactory {
    public static function buildOurProcess($fileName, $role){
        $file = new TxtFile($fileName);
        $fileProxy = new TxtFileProxy($file, $role);
        $fileProxy->open();
        $fileProxy->read();
        $fileProxy->write("2nees.com explain Proxy Pattern! - 1\n");
        $fileProxy->write("2nees.com explain Proxy Pattern! - 2\n");
        $fileProxy->read();
        $fileProxy->close();
        echo "===========================2-1=======================" . PHP_EOL;
    }
}

TxtFileFactory::buildOurProcess("2neesFile1-2", 1);
TxtFileFactory::buildOurProcess("2neesFile2-2", 2);

/**
 * Class TxtFileFactoryDirector - Another example Just to simulate some ideas like create director
 */
class TxtFileFactoryDirector {
    private TextFilesControl $builder;

    public function setBuilder(TextFilesControl $textFilesControl): void
    {
        $this->builder = $textFilesControl;
    }

    public function buildFullAccessRole(): void
    {
        $fileProxy = new TxtFileProxy($this->builder, 1);
        $fileProxy->open();
        $fileProxy->write("2nees.com explain Proxy Pattern! - 1\n");
        $fileProxy->write("2nees.com explain Proxy Pattern! - 2\n");
        $fileProxy->read();
        echo "==========================3-1========================" . PHP_EOL;
    }

    public function buildReadAccessRole(): void
    {
        $fileProxy = new TxtFileProxy($this->builder, 2);
        $fileProxy->open();
        $fileProxy->read();
        echo "==========================3-2========================" . PHP_EOL;
    }
}

$txtFileBuilder = new TxtFileFactoryDirector();
$txtFileBuilder->setBuilder(new TxtFile("2neesFile3-1"));
$txtFileBuilder->buildFullAccessRole();
$txtFileBuilder->buildReadAccessRole();

// What is needed from above methods just to keep your thinking work, you can think how we can improve our code always
// So that, may these methods not used in real world, or may you are create another thing, or a real factory method and so on...