<?php
/**
 * 2nees.com
 */
interface Button
{
    public function showText(): string;
}

abstract class Dialog
{
    /**
     * factory method.
     */
    abstract public function createButton(): Button;

    public function render(): string
    {
        // Call the factory method to create a Button object.
        $button = $this->createButton();
        // Now, use the button.
		$result = "My Button: " . $button->showText();

        return $result;
    }
}

/**
 * Concrete Creators override the factory method in order to change the
 * resulting Buttons type.
 * 2nees.com
 */
class WindowsDialog extends Dialog
{
    public function createButton(): Button
    {
        return new WindowsButton();
    }
}

class WebDialog extends Dialog
{
    public function createButton(): Button
    {
        return new HtmlButton();
    }
}


class WindowsButton implements Button
{
    public function showText(): string
    {
        return "Windows Okay Button";
    }
}

class HtmlButton implements Button
{
    public function showText(): string
    {
        return "<button>Html Button</button>";
    }
}

// Call the client tesult code
function clientCode(Dialog $dialog)
{
    echo $dialog->render();
}

// Create Windows Dialog
clientCode(new WindowsDialog());
echo "\n\n";
// Create Web Dialog
clientCode(new WebDialog());
