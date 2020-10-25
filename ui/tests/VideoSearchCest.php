<?php
use \Codeception\Example;

class VideoSearchCest
{
    /**
     * @example(searchQuery="Ураган", itemNumber="71")
     */
    public function videoShowsWhenHoverOnSearchResult(AcceptanceTester $I, Example $example)
    {
        $video_page = $I->openVideoPage();
        $video_search_page = $video_page->search($example['searchQuery']);
        $I->assertGreaterThan(0, count($video_search_page->getSearchItems()), "Empty search results");
        $video_search_page->hoverOnSearchItem($example['itemNumber']);
        $I->assertTrue($video_search_page->isSearchItemVideoPlaying($example['itemNumber']), "Video is not playing");
    }
}
