<?php
namespace Page\Acceptance;


abstract class BasePage
{
    public static string $URL;

    public static function route($param)
    {
        return static::$URL.$param;
    }

    protected \AcceptanceTester $acceptanceTester;

    public function __construct(\AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
    }

    public function open()
    {
        $this->acceptanceTester->amOnPage($this::$URL);
        return $this;
    }
}
