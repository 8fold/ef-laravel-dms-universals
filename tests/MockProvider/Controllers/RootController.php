<?php
declare(strict_types=1);

namespace Eightfold\DmsHelpers\Tests\MockProvider\Controllers;

use Eightfold\DmsHelpers\Controller;

use Eightfold\ShoopShelf\Shoop;

class RootController extends Controller
{
    static public function localRoot(): string
    {
        return Shoop::this(__DIR__)->divide("/")->dropLast(2)
            ->append(["content-folder"])->efToString("/");
    }

    public function __invoke(...$extras)
    {
        return Shoop::store(static::localRoot())->append(["content.md"])
            ->markdown()->html()->unfold();
    }
}
