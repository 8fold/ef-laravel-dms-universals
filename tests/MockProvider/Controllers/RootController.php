<?php
declare(strict_types=1);

namespace Eightfold\DmsHelpers\Tests\MockProvider\Controllers;

use Eightfold\DmsHelpers\AbstractBridge;

use Eightfold\Shoop\Shoop;

class RootController extends AbstractBridge
{
    static public function localRoot(): string
    {
        return Shoop::this(__DIR__)->divide("/")->dropLast(2)
            ->append(["content-folder"])->efToString("/");
    }

    public function __invoke(...$extras)
    {
        return "Hello, World!";
    }
}
