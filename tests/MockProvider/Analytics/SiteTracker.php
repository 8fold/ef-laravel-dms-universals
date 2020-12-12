<?php

namespace Eightfold\DmsHelpers\Tests\MockProvider\Analytics;

use Eightfold\DmsHelpers\Analytics\AbstractSiteTracker;

use Eightfold\ShoopShelf\Shoop;

class SiteTracker extends AbstractSiteTracker
{
    // static public function localRoot(): string
    // {
    //     return Shoop::this(__DIR__)->divide("/")->dropLast(2)
    //         ->append(["tracker-folder"])->efToString("/");
    // }
}
