<?php
namespace Page\Acceptance;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;
use PHPUnit\Framework\Assert;


class VideoSearchPage extends VideoPage
{
    public static string $URL = '/video/search';
    public static string $searchItem = '.serp-item';
    public static string $searchItemThumbImage = '.serp-item .thumb-image__image';
    public static string $searchItemThumbVideo = '.serp-item .thumb-preview__target_playing .thumb-preview__video';
    public static string $moreVideosButton = 'button.more__button';

    public function seeSearchItems()
    {
        $this->acceptanceTester->seeNumberOfElements($this::$searchItem, [1, 20]);
    }

    private function getSearchItem(int $itemNumber): WebDriverElement
    {
        $I = $this->acceptanceTester;

        $items = $I->findElements($this::$searchItem);

        while (count($items) < $itemNumber) {
            $I->scrollToElement(end($items));
            $items = $I->findElements($this::$searchItem);

            if ($I->tryToSee('Ещё видео', $this::$moreVideosButton)) {
                $I->Click($this::$moreVideosButton);
            }
        }

        return $items[$itemNumber];
    }

    public function seeSearchItemPreview(int $itemNumber)
    {
        $item = $this->getSearchItem($itemNumber);

        try {
            $video = $item->findElement(WebDriverBy::cssSelector($this::$searchItemThumbVideo));
            $this->acceptanceTester->seeElementIsVisible($video);
        } catch (\Exception $e) {
            Assert::fail("Can't find video for ${itemNumber} item. Details:\n${e}");
        }
    }

    public function hoverOnSearchItem(int $itemNumber)
    {
        $item = $this->getSearchItem($itemNumber);
        $this->acceptanceTester->moveMouseOverElement($item);
        $this->acceptanceTester->waitForElementVisible($this::$searchItemThumbVideo);
    }
}
