<?php
/**
 * This example just to simulate how Association can be work
 * 2nees.com
 */

class Website {
    private string $content;

    /**
     * Website constructor.
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function printContent(WebsiteContent $websiteContent): void {
        $websiteContent->setText($this->content);
        $websiteContent->print();
    }
}

abstract class WebsiteContent {
    protected string $text;

    public function setText($text): void {
        $this->text = $text;
    }

    abstract function print(): void;
}

class ListContent extends WebsiteContent {
    function print(): void
    {
        echo "<ul><li>{$this->text}</li></ul>" . PHP_EOL;
    }
}

class ParagraphContent extends WebsiteContent {
    function print(): void
    {
        echo "<p>{$this->text}</p>" . PHP_EOL;
    }
}

// Client
$listContent = new ListContent();
$paragraphContent = new ParagraphContent();

$website = new Website("2nees.com");
$website->printContent($listContent);
$website->printContent($paragraphContent);
unset($website);
// Since its Association relation, other object can complete life cycle normally...
$listContent->setText("List will not effect if we remove website, I'm Association With Website!");
$listContent->print();
$paragraphContent->setText("Paragraph will not effect if we remove website, I'm Association With Website!");
$paragraphContent->print();