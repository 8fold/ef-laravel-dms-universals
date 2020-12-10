<?php
declare(strict_types=1);

namespace Eightfold\DmsHelpers\Tests\MockProvider\ContentHelpers;

use Eightfold\DmsHelpers\ContentHelpers\AbstractTitle;

use Eightfold\ShoopShelf\Shoop;

class Title extends AbstractTitle
{
    static public function localRoot(): string
    {
        return Shoop::this(__DIR__)->divide("/")->dropLast(2)
            ->append(["content-folder"])->efToString("/");
    }
}
