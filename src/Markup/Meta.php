<?php

namespace Eightfold\DmsHelpers\Markup;

use Eightfold\DmsHelpers\Markup;

use Eightfold\ShoopShelf\Shoop;
use Eightfold\ShoopShelf\FluentTypes\ESStore;
use Eightfold\Markup\UIKit;

use Eightfold\DmsHelpers\Markup\Title;

abstract class Meta extends Markup
{
    private $type = "website";

    abstract public function title(): string;

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
            $this->title(),
            url()->current(),
            $this->description(),
            $this->poster(),
            $this->type()
        ];
    }

    public function description(): string
    {
        $store = Shoop::store($this->local())
            ->append($this->requestPathParts())
            ->append(["content.md"]);
        if ($store->isFile()->unfold()) {
            $meta = $store->markdown()->meta();
            if ($meta->hasAt("description")->unfold()) {
                return $meta->at("description")->unfold();

            } elseif ($store->markdown()->body()->isEmpty()->reversed()->unfold()) {
                $description = $store->markdown()->body()->divide("\n\n")->each(
                    function($block, $i, &$build, &$break) {
                        $block = trim($block);
                        $block = Shoop::this($block);
                        if ($block->startsWith("#")->reversed()->unfold()) {
                            $break = true;
                            $build[] = $block->unfold();
                        }
                    }
                )->first()->divide(" ", false);

                if ($description->length()->isGreaterThan(50)->unfold()) {
                    return $description->first(50)->asString(" ")->append("...")
                        ->unfold();
                }
                return $description->efToString(" ");
            }
        }
        return "";
    }

    public function poster(): string
    {
        $store = Shoop::store($this->local())->append([".media"])
            ->append($this->requestPathParts());

        $posters = Shoop::this($this->requestPathParts())->each(
            function($p) use (&$store) {
                $content = $store->append([".images", "poster.png"]);
                if ($content->isFile()->unfold()) {
                    $store = $store->up();
                    return $content->unfold();
                }
        })->drop(fn($p) => empty($p));

        if ($posters->efIsEmpty() and
            $this->rootPosterStore()->isFile()->unfold()
        ) {
            $posters = $posters->append([$this->rootPosterStore()->unfold()]);
        }

        return url(
            $posters->first()->divide(".media", false, 2)->last()
                ->divide(".images", false, 2)->first()
                ->prepend("/media/images")->append("poster.png")->unfold()
        );
    }

    private function rootPosterStore(): ESStore
    {
        return Shoop::store($this->local())->append([
            ".media",
            ".images",
            "poster.png"
        ]);
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
