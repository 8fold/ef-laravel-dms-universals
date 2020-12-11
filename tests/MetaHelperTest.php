<?php

namespace Eightfold\DmsHelpers\Tests;

use Orchestra\Testbench\BrowserKit\TestCase;
use Eightfold\Foldable\Tests\PerformantEqualsTestFilter as AssertEquals;

use Eightfold\DmsHelpers\Tests\MockProvider\ContentHelpers\Meta;

/**
 * @group Meta
 */
class MetaHelperTest extends TestCase
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
            10.19,
            425 // 424 // 411
        )->unfoldUsing(
            Meta::localRoot()
        );
    }

    /**
     * @test
     */
    public function meta_is_expected()
    {
        AssertEquals::applyWith(
            '<meta name="viewport" content="width=device-width,initial-scale=1"><link type="image/x-icon" rel="icon" href="/assets/favicons/favicon.ico"><link rel="apple-touch-icon" href="/assets/favicons/apple-touch-icon.png" sizes="180x180"><link rel="image/png" href="/assets/favicons/favicon-32x32.png" sizes="32x32"><link rel="image/png" href="/assets/favicons/favicon-16x16.png" sizes="16x16"><meta content="website" property="og:type"><meta content="Root title" property="og:title"><meta content="http://localhost" property="og:url"><meta content="Description unavailable" property="og:description"><meta content="http://localhost/media/poster.png" property="og:image"><link rel="stylesheet" href="/css/main.css"><script src="/js/main.js"></script>',
            "string",
            33.35, // ^ 23.21, // 22.07, // ^ 11.88, // 11.69, // 11.29,
            1705 // ^ 609
        )->unfoldUsing(
            Meta::fold("/")
        );
    }

    /**
     * @test
     */
    public function description_is_expected()
    {
        AssertEquals::applyWith(
            '',
            "string",
            10.56, // 0.47, // 0.41,
            1072
        )->unfoldUsing(
            Meta::fold("/")->description()
        );

        AssertEquals::applyWith(
            'From frontmatter',
            "string",
            10.21, // 9.79, // 9.4, // 9.24,
            1063
        )->unfoldUsing(
            Meta::fold("/hello")->description()
        );

        AssertEquals::applyWith(
            'This is the first paragraph of the content and needs to be long to be used with truncating methods. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ut nibh id velit suscipit maximus eget sed est. Pellentesque sit amet nisl elit. Phasellus laoreet euismod libero at commodo. Orci...',
            "string",
            11.14, // 9.55, // 9.28,
            1072
        )->unfoldUsing(
            Meta::fold("/hello/world")->description()
        );
    }

    /**
     * @test
     */
    public function poster_is_expected()
    {
        AssertEquals::applyWith(
            'http://localhost/media/poster.png',
            "string",
            6.24, // ^ 5.96, // 5.87, // 5.47, // 4.94,
            570 // ^ 566 // 533
        )->unfoldUsing(
            Meta::fold("/")->poster()
        );

        AssertEquals::applyWith(
            'http://localhost/media/hello/world/poster.png',
            "string",
            5.8,
            570
        )->unfoldUsing(
            Meta::fold("/hello/world")->poster()
        );

        AssertEquals::applyWith(
            'http://localhost/media/poster.png',
            "string",
            0.53, // 0.36,
            1
        )->unfoldUsing(
            Meta::fold("/hello")->poster()
        );
    }
}
