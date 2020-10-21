<?php
class VideoSearchCest
{
    public function videoShowsWhenHoverOnSearchResult(AcceptanceTester $I)
    {
        $video_page = $I->openVideoPage();
        $video_search_page = $video_page->search('Ураган');
        $video_search_page->seeSearchItems();
        $video_search_page->hoverOnSearchItem(12);
        $video_search_page->seeSearchItemPreview(12);
    }
}
