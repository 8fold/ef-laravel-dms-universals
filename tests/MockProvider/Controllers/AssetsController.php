<?php
declare(strict_types=1);

namespace Eightfold\DmsHelpers\Tests\MockProvider\Controllers;

use Eightfold\DmsHelpers\Controllers\AbstractImageController;

use Eightfold\Shoop\Shoop;

class AssetsController extends AbstractImageController
{
    static public function localRoot(): string
    {
        return Shoop::this(__DIR__)->divide("/")->dropLast(2)
            ->append(["content-folder", ".assets"])->efToString("/");
    }
}
