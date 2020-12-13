<?php
declare(strict_types=1);

namespace Eightfold\DmsHelpers\Tests\MockProvider\Controllers;

use Eightfold\DmsHelpers\Controllers\FileController;

use Eightfold\Shoop\Shoop;

class MediaController extends FileController
{
    static public function localRoot(): string
    {
        return Shoop::this(__DIR__)->divide("/")->dropLast(2)
            ->append(["content-folder", ".media"])->efToString("/");
    }

    static public function pathPrefix(): string
    {
        return "media";
    }
}
