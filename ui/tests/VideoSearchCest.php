<?php
use \Codeception\Example;

class VideoSearchCest
{
    /**
     * @example(searchQuery="Ураган", itemNumber="12")
     */
    public function videoShowsWhenHoverOnSearchResult(AcceptanceTester $I, Example $example)
    {
        $video_page = $I->openVideoPage();
        $video_search_page = $video_page->search($example['searchQuery']);
        $video_search_page->seeSearchItems();
        $video_search_page->hoverOnSearchItem($example['itemNumber']);
        $video_search_page->seeSearchItemPreview($example['itemNumber']);
    }
}
