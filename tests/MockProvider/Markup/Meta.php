<?php
declare(strict_types=1);

namespace Eightfold\DmsHelpers\Tests\MockProvider\Markup;

use Eightfold\DmsHelpers\Markup\Meta as AbstractMeta;

use Eightfold\ShoopShelf\Shoop;

class Meta extends AbstractMeta
{
    static public function localRoot(): string
    {
        return Shoop::this(__DIR__)->divide("/")->dropLast(2)
            ->append(["content-folder"])->efToString("/");
    }
}
