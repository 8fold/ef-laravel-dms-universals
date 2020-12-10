<?php

namespace Eightfold\DmsHelpers\Tests;

use Orchestra\Testbench\BrowserKit\TestCase;
use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use Eightfold\DmsHelpers\Tests\MockProvider\Controllers\AssetsController;
use Eightfold\DmsHelpers\Tests\MockProvider\Controllers\MediaController;

class RouteHelperTest extends TestCase
{
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

    /**
     * @test
     */
    public function images()
    {
        AssertEquals::applyWith(
            200,
            "integer",
            17.9, // 17.19,
            3632 // 3628
        )->unfoldUsing(
            $this->call("GET", "/assets/favicons/favicon.ico")->getStatusCode()
        );

        AssertEquals::applyWith(
            200,
            "integer",
            1,
            2
        )->unfoldUsing(
            $this->call("GET", "/media/poster.png")->getStatusCode()
        );
    }
}
