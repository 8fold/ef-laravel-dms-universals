<?php
declare(strict_types=1);

namespace Eightfold\DmsHelpers\Controllers;

use Eightfold\ShoopShelf\Shoop;

abstract class AbstractImageController
{
    abstract static public function localRoot(): string;

    public function __invoke(...$extras)
    {
        $extras = Shoop::this($extras);
        if (Shoop::this(static::localRoot())->divide("/")->last()->is(".assets")->unfold() and
            $extras->length()->is(2)->reversed()->unfold()
        ) {
            abort(404);
        }

        $store = Shoop::store(static::localRoot())->append($extras->unfold());
        if ($store->isFile()->reversed()->unfold()) {
            abort(404);
        }

        $extension = $extras->last()->divide(".")->last()->unfold();

        return response()->file(
            $store->unfold(),
            ["Content-Type: image/{$extension}"]
        );
    }
}
