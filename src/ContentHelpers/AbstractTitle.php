<?php

namespace Eightfold\DmsHelpers\ContentHelpers;

use Eightfold\Foldable\Fold;

use Eightfold\ShoopShelf\Shoop;

abstract class AbstractTitle extends Fold
{
    abstract static public function localRoot(): string;

    private $uriString; // path for user (web-client) request; where the user is.

    public function __construct(string $uriString)
    {
        $this->uriString = $uriString;
    }

    protected function uriString(): string
    {
        return $this->uriString;
    }

    protected function uriParts(): array
    {
        return Shoop::this($this->uriString())->divide("/")
            ->drop(fn($p) => empty($p))->efToArray();
    }

    public function unfold(): string
    {
        $store = Shoop::store($this->localRoot())->append($this->uriParts());
        $titles = Shoop::this($this->uriParts())->each(function($p) use (&$store) {
            $content = $store->append(["content.md"]);
            if ($content->isFile()->unfold() and
                $content->markdown()->meta()->hasAt("title")->unfold()
            ) {
                $store = $store->up();
                return $content->markdown()->meta()->at("title")->unfold();
            }
        });

        if (Shoop::store(static::localRoot())->append(["content.md"])
                ->isFile()->unfold()
        ) {
            $meta = Shoop::store(static::localRoot())->append(["content.md"])
                ->markdown()->meta();
            if ($meta->hasAt("title")->unfold()) {
                $titles = $titles->append([$meta->at("title")->unfold()]);
            }
        }

        return $titles->efToString(" | ");
    }
}
