<?php

namespace Eightfold\DmsHelpers\Tests;

use Orchestra\Testbench\BrowserKit\TestCase;
use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use Eightfold\ShoopShelf\Shoop;

use Eightfold\DmsHelpers\Tests\MockProvider\Analytics\SiteTracker;

/**
 * @group Analytics
 */
class AalyticsTest extends TestCase
{
    protected function tearDown(): void
    {
        Shoop::store(__DIR__)->append(["tracker-folder"])->delete();
    }

    protected function getPackageProviders($app)
    {
        return ['Eightfold\DmsHelpers\Tests\MockProvider\Provider'];
    }

    /**
     * @test
     */
    public function local_root_is_expected()
    {
        AssertEquals::applyWith(
            __DIR__ ."/tracker-folder",
            "string",
            16.31,
            398
        )->unfoldUsing(
            SiteTracker::fold(__DIR__ ."/tracker-folder", 1234)->local()
        );
    }

    /**
     * @test
     */
    public function session_id_is_expected()
    {
        AssertEquals::applyWith(
            "1234",
            "string",
            0.26,
            10
        )->unfoldUsing(
            SiteTracker::fold("1234")->sessionId()
        );
    }

    /**
     * @test
     */
    public function session_file_is_saved()
    {
        AssertEquals::applyWith(
            true,
            "boolean",
            0.23,
            11
        )->unfoldUsing(
            SiteTracker::fold("1234")->savedRequest()
        );
    }

    /**
     * @test
     */
    public function crawler_is_expected()
    {
        AssertEquals::applyWith(
            true,
            "boolean",
            19.82,
            2779 // 2778
        )->unfoldUsing(
            SiteTracker::fold(__DIR__ ."/tracker-folder", "1234")->savedRecord()
        );
    }
}
