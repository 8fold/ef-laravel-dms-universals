<?php

namespace Eightfold\DmsHelpers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Eightfold\ShoopShelf\Shoop;

/**
 * When using an invokable Laravel controller, there cannot be a custom constructor.
 * The reason for this seems to be that the Laravel's IoC combined with the router
 * will attempt to interpret what the constructor is requesting; it expects the
 * values to be sent from the router - not the developer using the invokable.
 *
 * Therefore, this controller effectively replicates the App\Http\Controller
 * from a base Laravel install, with the added capability of bridging into the
 * local file system by declaring the root folder in which content is stored.
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    abstract static public function localRoot(): string;

    protected function local(): string
    {
        return static::localRoot();
    }

    protected function requestPath(): string
    {
        $path = Shoop::this(
            request()->path()
        );
        if ($path->startsWith("/")->reversed()->unfold()) {
            return $path->prepend("/")->unfold();
        }
        return $path->unfold();
    }

    protected function requestPathParts(): array
    {
        return Shoop::this(
            $this->requestPath()
        )->divide("/")->drop(fn($p) => empty($p))->efToArray();
    }
}
