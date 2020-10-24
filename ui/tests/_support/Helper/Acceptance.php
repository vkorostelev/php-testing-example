<?php
namespace Helper;

use Facebook\WebDriver\WebDriver;
use Facebook\WebDriver\WebDriverElement;

class Acceptance extends \Codeception\Module
{
    public function findElement(string $selector)
    {
        $elements = $this->getModule('WebDriver')->_findElements($selector);
        return reset($elements);
    }

    public function findElements(string $selector)
    {
        return $this->getModule('WebDriver')->_findElements($selector);
    }

    public function moveMouseOverElement(WebDriverElement $element = null, int $offsetX = null, int $offsetY = null)
    {
        $where = null;

        if (isset($element)) {
            $element->getLocationOnScreenOnceScrolledIntoView();
            $where = $element->getCoordinates();
        }

        $this->getModule('WebDriver')->webDriver->getMouse()->mouseMove($where, $offsetX, $offsetY);
    }

    public function scrollToElement(WebDriverElement $element, int $offsetX = null,  int $offsetY = null)
    {
        $x = $element->getLocation()->getX() + $offsetX;
        $y = $element->getLocation()->getY() + $offsetY;
        $this->getModule('WebDriver')->webDriver->executeScript("window.scrollTo($x, $y)");
    }

    public function seeElementIsVisible(WebDriverElement $element)
    {
        $elementClass = $element->getAttribute('class');
        $this->assertTrue($element->isDisplayed(), "Element ${elementClass} is not visible");
    }

    public function isElementPresent($selector, $timeout = 10)
    {
        try {
            $this->getModule('WebDriver')->waitForElement($selector, $timeout);
            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    public function waitFor(callable $func_or_ec, int $timeout = 10, int $interval = 200)
    {
        $webDriverModule = $this->getModule('WebDriver');
        return $webDriverModule->executeInSelenium(
            function (WebDriver $webdriver) use ($func_or_ec, $timeout, $interval) {
                return $webdriver->wait($timeout, $interval)->until($func_or_ec);
            });
    }
}
