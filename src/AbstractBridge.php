<?php

namespace Eightfold\DmsHelpers;

use Eightfold\Foldable\Fold;

use Eightfold\ShoopShelf\Shoop;

/**
 * An abstract class for bridging between remote client paths and local file paths.
 */
abstract class AbstractBridge extends Fold
{
    /**
     * The remote client path.
     *
     * Always start with forward-slash. Never include file.
     *
     * ex (root): /
     * ex (sub):  /user/path/to/view
     */
    private $clientPath;

    /**
     * The root local directory for files handled by subclass.
     */
    abstract static public function localRoot(): string;

    /**
     * @todo Laravel routes that use invokable controllers do not seem to accept a
     * custom constructor.
     *
     * @param string $clientPath [description]
     */
    public function __construct(string $clientPath)
    {
        $this->clientPath = $clientPath;
    }

    protected function local(): string
    {
        return static::localRoot();
    }

    protected function clientPath(): string
    {
        return $this->clientPath;
    }

// -> Overrides

    protected function clientPathParts(): array
    {
        return Shoop::this($this->clientPath())->divide("/")
            ->drop(fn($p) => empty($p))->efToArray();
    }

}
