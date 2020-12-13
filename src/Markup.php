<?php

namespace Eightfold\DmsHelpers;

use Eightfold\Foldable\Fold;

use Eightfold\ShoopShelf\Shoop;

abstract class Markup extends Fold
{
    abstract static public function localRoot(): string;

    public function local()
    {
        return static::localRoot();
    }

    protected function requestPath(): string
    {
        $path = Shoop::this(
            request()->path()
        );

        return ($path->startsWith("/")->unfold())
            ? $path->unfold()
            : $path->prepend("/")->unfold();
    }

    protected function requestPathParts(): array
    {
        return Shoop::this(
            $this->requestPath()
        )->divide("/")->drop(fn($p) => empty($p))->efToArray();
    }
}
