<?php
namespace Page\Acceptance;


class VideoPage extends BasePage
{
    public static string $URL = '/video';
    public static string $searchField = '.search2__input input[name=text]';
    public static string $searchButton = '.search2__button button[type=submit]';

    public function search($search_query): VideoSearchPage
    {
        $I = $this->acceptanceTester;

        $I->fillField($this::$searchField, $search_query);
        $I->click($this::$searchButton);
        return new VideoSearchPage($I);
    }
}
