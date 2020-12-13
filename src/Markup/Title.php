<?php

namespace Eightfold\DmsHelpers\Markup;

use Eightfold\DmsHelpers\Markup;

use Eightfold\ShoopShelf\Shoop;

abstract class Title extends Markup
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

    public function unfold(): string
    {
        $store = Shoop::store($this->local())->append($this->requestPathParts());
        $titles = Shoop::this($this->requestPathParts())->each(
            function($p) use (&$store) {
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
