<?php
namespace Page\Acceptance;

use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;


class VideoSearchPage extends VideoPage
{
    public static string $URL = '/video/search';
    public static string $searchItem = '.serp-item';
    public static string $searchItemThumbImage = '.serp-item .thumb-image__image';
    public static string $searchItemThumbVideo = '.serp-item .thumb-preview__target_playing .thumb-preview__video';
    public static string $moreVideosButton = '.more_last_yes button.more__button';
    public static string $moreHiddenButton = '.more_disabled_yes .more__button_hidden_yes';

    public function getSearchItems(): array
    {
        return $this->acceptanceTester->findElements($this::$searchItem);
    }

    private function getSearchItem(int $itemNumber): WebDriverElement
    {
        $I = $this->acceptanceTester;

        $items = $this->getSearchItems();
        $itemsCount = count($items);

        while ($itemsCount < $itemNumber) {
            // Infinite scroll
            if ($I->isElementPresent($this::$moreHiddenButton, 2)) {
                $I->scrollToElement(end($items));
            }
            // Pagination button
            elseif ($I->isElementPresent($this::$moreVideosButton, 2)) {
                $I->Click($this::$moreVideosButton);
            }
            // End of the list
            else {
                throw new NoSuchElementException("Can't find the ${itemNumber}'th element in search Results.");
            }

            $items = $I->waitFor(function() use ($itemsCount) {
                $items = $this->getSearchItems();
                return count($items) > $itemsCount ? $items : null;
            }, 5);
            $itemsCount = count($items);
        }

        return $items[$itemNumber];
    }

    public function isSearchItemVideoPlaying(int $itemNumber): bool
    {
        $item = $this->getSearchItem($itemNumber);

        try {
            $video = $item->findElement(WebDriverBy::cssSelector($this::$searchItemThumbVideo));
            return $this->acceptanceTester->waitFor(function () use ($video) {
                return $this->acceptanceTester->executeJS('return (
                    arguments[0].readyState > 2
                    && arguments[0].currentTime > 1
                    && !arguments[0].paused
                    && !arguments[0].ended
                );', [$video]);
            }, 3);
        } catch (NoSuchElementException | TimeoutException $e) {
            return false;
        }
    }

    public function hoverOnSearchItem(int $itemNumber)
    {
        $item = $this->getSearchItem($itemNumber);
        $this->acceptanceTester->moveMouseOverElement($item);
        $this->acceptanceTester->waitForElementVisible($this::$searchItemThumbVideo);
    }
}
