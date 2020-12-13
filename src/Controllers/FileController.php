<?php
declare(strict_types=1);

namespace Eightfold\DmsHelpers\Controllers;

use Eightfold\DmsHelpers\Controller;

use Eightfold\ShoopShelf\Shoop;

abstract class FileController extends Controller
{
    abstract static public function localRoot(): string;

    abstract static public function pathPrefix(): string;

    public function __invoke(...$extras)
    {
        $extras = $extras[0];
        $extras = Shoop::this($extras)->divide("/");
        if ($extras->length()->isGreaterThanOrEqualTo(2)->reversed()->unfold()) {
            abort(404);
        }

        $store = Shoop::store(static::localRoot())->append([
            $extras->first()->prepend(".")->unfold()
        ])->append(
            $extras->dropFirst()->unfold()
        );

        if ($store->isFile()->reversed()->unfold()) {
            abort(404);
        }

        return response()->file(
            $store->unfold(),
            [$store->mimeType()->prepend("Content-Type: ")->unfold()]
        );
    }
}
