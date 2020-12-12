<?php

namespace Eightfold\DmsHelpers\ContentHelpers;

use Eightfold\DmsHelpers\AbstractBridge;

use Eightfold\ShoopShelf\Shoop;

abstract class AbstractTitle extends AbstractBridge
{
    public function unfold(): string
    {
        $store = Shoop::store($this->local())->append($this->clientPathParts());
        $titles = Shoop::this($this->clientPathParts())->each(function($p) use (&$store) {
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
