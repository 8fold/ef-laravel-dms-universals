<?php
declare(strict_types=1);

namespace Eightfold\DmsHelpers\Controllers;

use Eightfold\DmsHelpers\AbstractBridge;

use Eightfold\ShoopShelf\Shoop;

abstract class AbstractImageController extends AbstractBridge
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
