<?php

namespace Eightfold\DmsHelpers\ContentHelpers;

use Eightfold\DmsHelpers\AbstractBridge;

use Eightfold\ShoopShelf\Shoop;
use Eightfold\Markup\UIKit;

use Eightfold\DmsHelpers\Tests\MockProvider\ContentHelpers\Title;

abstract class AbstractMeta extends AbstractBridge
{
    private $type = "website";

    public function type(string $type = "")// : string|static
    {
        if (Shoop::this($type)->efIsEmpty()) {
            return $this->type;
        }
        $this->type = $type;
        return $this;
    }

// -> Overrides

    public function styles(): array
    {
        return ["/css/main.css"];
    }

    public function scripts(): array
    {
        return ["/js/main.js"];
    }

    public function social(): array
    {
        $scheme = "http";
        if (Shoop::this($_SERVER)->hasAt("HTTPS")->unfold() and
            Shoop::this($_SERVER)->at("HTTPS")->is("on")->unfold()
        ) {
            $scheme = "https";
        }

        return [
            Title::fold($this->local(), $this->clientPath())->unfold(),
            url()->current(),
            $this->description(),
            $this->poster(),
            $this->type()
        ];
    }

    public function description(): string
    {
        $store = Shoop::store($this->local())
            ->append($this->clientPathParts())
            ->append(["content.md"]);
        if ($store->isFile()->unfold()) {
            $meta = $store->markdown()->meta();
            if ($meta->hasAt("description")->unfold()) {
                return $meta->at("description")->unfold();

            } elseif ($store->markdown()->body()->isEmpty()->reversed()->unfold()) {
                return $store->markdown()->body()->divide("\n\n")->each(
                    function($block, $i, &$build, &$break) {
                        $block = trim($block);
                        $block = Shoop::this($block);
                        if ($block->startsWith("#")->reversed()->unfold()) {
                            $break = true;
                            $build[] = $block->unfold();
                        }
                    }
                )->first()->divide(" ", false, 50)->dropLast()->asString(" ")
                ->append("...")->unfold();
            }
        }
        return "";
    }

    public function poster(): string
    {
        $store = Shoop::store(static::localRoot())->append([".media"])
            ->append($this->clientPathParts());
        $posters = Shoop::this($this->clientPathParts())->each(function($p) use (&$store) {
            $content = $store->append(["poster.png"]);
            if ($content->isFile()->unfold()) {
                $store = $store->up();
                return $content->unfold();
            }
        })->drop(fn($p) => empty($p));

        if ($posters->efIsEmpty() and
            Shoop::store(static::localRoot())->append([".media", "poster.png"])
                ->isFile()->unfold()
        ) {
            $posters = $posters->append([
                Shoop::store(static::localRoot())->append([".media", "poster.png"])->unfold()
            ]);
        }
        return url(
            $posters->first()->divide(".media", false, 2)->last()->prepend("/media")->unfold()
        );
    }

    public function unfold(): string
    {
        return UIKit::webHead()->favicons(
            "/assets/favicons/favicon.ico",
            "/assets/favicons/apple-touch-icon.png",
            "/assets/favicons/favicon-32x32.png",
            "/assets/favicons/favicon-16x16.png"
        )->social(
            ...$this->social()
        )->styles(
            ...$this->styles()
        )->scripts(
            ...$this->scripts()
        )->unfold();
    }
}
