<?php

namespace Eightfold\DmsHelpers\Tests;

use Orchestra\Testbench\BrowserKit\TestCase;
use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use Eightfold\DmsHelpers\Tests\MockProvider\ContentHelpers\Title;

class TitleHelperTest extends TestCase
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
            __DIR__ ."/content-folder",
            "string",
            8.07,
            401
        )->unfoldUsing(
            Title::localRoot()
        );
    }

    /**
     * @test
     * @group current
     */
    public function title_is_expected()
    {
        AssertEquals::applyWith(
            "Root title",
            "string",
            10.45, // 10.24, // 9.44, // 9.38, // ^ 5.23, // ^ 0.34, // 0.33, // 0.32,
            1051 // ^ 428 // 419 // 418 // 417 // ^ 12
        )->unfoldUsing(
            Title::fold("/")->unfold()
        );

        AssertEquals::applyWith(
            "Hello | Root title",
            "string",
            0.82, // 0.81, // 0.71,
            1
        )->unfoldUsing(
            Title::fold("/hello")->unfold()
        );

        AssertEquals::applyWith(
            "World | Hello | Root title",
            "string",
            10.15,
            1
        )->unfoldUsing(
            Title::fold("/hello/world")->unfold()
        );
    }
}
