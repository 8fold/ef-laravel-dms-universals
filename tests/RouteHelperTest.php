<?php

namespace Eightfold\DmsHelpers\Tests;

use Orchestra\Testbench\BrowserKit\TestCase;
use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use Eightfold\DmsHelpers\Tests\MockProvider\Controllers\RootController;
use Eightfold\DmsHelpers\Tests\MockProvider\Controllers\AssetsController;
use Eightfold\DmsHelpers\Tests\MockProvider\Controllers\MediaController;

/**
 * @group Route
 */
class RouteHelperTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return ['Eightfold\DmsHelpers\Tests\MockProvider\Provider'];
    }

    /**
     * @test
     */
    public function root_has_expected_content()
    {
        $this->visit("/hello")->see("Hello, World!");

        $response = $this->call("GET", "/assets/favicons/favicon.ico");

        $this->assertEquals(200, $response->getStatusCode());

        $response = $this->call("GET", "/media/images/poster.png");

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function local_root_is_expected()
    {
        AssertEquals::applyWith(
            __DIR__ ."/content-folder",
            "string",
            8.93,
            321
        )->unfoldUsing(
            RootController::localRoot()
        );

        AssertEquals::applyWith(
            __DIR__ ."/content-folder/.assets",
            "string",
            4.43, // 3.88, // 3.53,
            390 // 334 // 330
        )->unfoldUsing(
            AssetsController::localRoot()
        );

        AssertEquals::applyWith(
            __DIR__ ."/content-folder/.media",
            "string",
            0.08, // 0.07, // 0.06,
            1
        )->unfoldUsing(
            MediaController::localRoot()
        );
    }
}
