<?php

namespace Eightfold\DmsHelpers\ContentHelpers;

use Eightfold\Foldable\Fold;

use Eightfold\ShoopShelf\Shoop;

abstract class AbstractMeta extends Fold
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
        // $store = Shoop::store($this->localRoot())->append($this->uriParts());
        // $titles = Shoop::this($this->uriParts())->each(function($p) use (&$store) {
        //     $content = $store->append(["content.md"]);
        //     if ($content->isFile()->unfold() and
        //         $content->markdown()->meta()->hasAt("title")->unfold()
        //     ) {
        //         $store = $store->up();
        //         return $content->markdown()->meta()->at("title")->unfold();
        //     }
        // });

        // if (Shoop::store(static::localRoot())->append(["content.md"])
        //         ->isFile()->unfold()
        // ) {
        //     $meta = Shoop::store(static::localRoot())->append(["content.md"])
        //         ->markdown()->meta();
        //     if ($meta->hasAt("title")->unfold()) {
        //         $titles = $titles->append([$meta->at("title")->unfold()]);
        //     }
        // }

        // return $titles->efToString(" | ");
    }

    // private $type = "website";

    // private function uriHas(string $uri, string $needle): bool
    // {
    //     $uri = Shoop::this($uri);
    //     if ($uri->is("/")->unfold()) {
    //         return false;
    //     }

    //     if ($uri->startsWith("/")->reversed()->unfold()) {
    //         $uri = $uri->prepend("/");
    //     }

    //     if ($uri->divide("/")->has($needle)->unfold()) {
    //         return true;
    //     }
    //     return false;
    // }

    // // TODO: Maybe this should be inside Store; not sure if it's scope creep
    // //      for Store given that Store is the bridge between the browser and
    // //      file paradigms.
    // private function currentUri(): string
    // {
    //     $uri = request()->path();
    //     if (Shoop::this($uri)->divide("/")->efIsEmpty()) {
    //         return "/";
    //     }
    //     return $uri;
    // }

    // private function description(): string
    // {
    //     $meta = Store::contentFile($this->store()->unfold())->markdown()->meta();
    //     if ($meta->hasAt("description")->unfold()) {
    //         return $meta->at("description")->unfold();
    //     }

    //     $titles = PageTitle::fold($this->store())->bookend()->titles();
    //     if (Shoop::this($titles)->efIsEmpty()) {
    //         return "no description provided";
    //     }
    //     return Shoop::this($titles)->reversed()->efToString(" | ");
    // }

    // public function socialImage(): string
    // {
    //     $store = Store::media(
    //         $this->store()->root()
    //     )->append(
    //         $this->store()->plus()
    //     );

    //     if ($store->hasPoster()) {
    //         $rootLength = Shoop::this($this->store()->root())->count();
    //         $path       = Shoop::this($store->posterPath())
    //             ->dropFirst($rootLength)->unfold();

    //         $path   = str_replace("/.media", "/media", $path);
    //         $domain = request()->root();

    //         return Shoop::this($domain)->append($path)->unfold();

    //     } elseif (! $store->isRoot()) {
    //         $foundPoster = false;
    //         while (! $store->isRoot() and ! $foundPoster) {
    //             $store = $store->up();
    //             $foundPoster = $store->hasPoster();
    //         }

    //         // TODO: Refactor
    //         $rootLength = Shoop::this($this->store()->root())->count();
    //         $path       = Shoop::this($store->posterPath())
    //             ->dropFirst($rootLength)->unfold();

    //         $path   = str_replace("/.media", "/media", $path);
    //         $domain = request()->root();

    //         return Shoop::this($domain)->append($path)->unfold();
    //     }

    //     trigger_error("Poster image in png or jpg format must be present");
    // }

    // public function styles(): array
    // {
    //     $styles = ["/css/le.css"];
    //     $uri = $this->currentUri();
    //     if ($this->uriHas($uri, "events")) {
    //         $styles[] = "/css/le-calendar.css";
    //     }
    //     return $styles;
    // }

    // public function scripts(): array
    // {
    //     $scripts  = ["/js/le-menu.js"];
    //     // TODO: Pattern must check root elements or URI
    //     $uri = $this->currentUri();
    //     if ($this->uriHas($uri, "events")) {
    //         $scripts[] = "/js/le-calendar.js";
    //     }

    //     if (env("APP_ENV") === "production" and $this->uriHas($uri, "contact")) {
    //         $scripts[] = "https://www.google.com/recaptcha/api.js";
    //     }
    //     return $scripts;
    // }

    // public function social(): array
    // {
    //     return [
    //         PageTitle::fold($this->store())->bookend()->unfold(),
    //         url()->current(),
    //         $this->description(),
    //         $this->socialImage(),
    //         $this->type
    //     ];
    // }

    // public function unfold(): string
    // {
    //     return UIKit::webHead()->favicons(
    //         "/assets/favicons/favicon.ico",
    //         "/assets/favicons/apple-touch-icon.png",
    //         "/assets/favicons/favicon-32x32.png",
    //         "/assets/favicons/favicon-16x16.png"
    //     )->styles(
    //         ...$this->styles()
    //     )->scripts(
    //         ...$this->scripts()
    //     )->social(
    //         ...$this->social()
    //     );

    //     return UIKit::webHead()->favicons(
    //         "/assets/favicons/favicon.ico",
    //         "/assets/favicons/apple-touch-icon.png",
    //         "/assets/favicons/favicon-32x32.png",
    //         "/assets/favicons/favicon-16x16.png"
    //     )->social(
    //         PageTitle::fold()->bookend()->unfold(),
    //         url()->current(),
    //         $this->description(),
    //         $this->socialImage(),
    //         $type
    //     )->socialTwitter(...$this->socialTwitter())
    //     ->styles(...$this->styles())
    //     ->scripts(...$this->scripts())
    //     ->unfold();
    // }
}
