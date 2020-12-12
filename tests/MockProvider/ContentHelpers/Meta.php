<?php
declare(strict_types=1);

namespace Eightfold\DmsHelpers\Tests\MockProvider\ContentHelpers;

use Eightfold\DmsHelpers\ContentHelpers\AbstractMeta;

use Eightfold\ShoopShelf\Shoop;

class Meta extends AbstractMeta
{
    static public function localRoot(): string
    {
        return Shoop::this(__DIR__)->divide("/")->dropLast(2)
            ->append(["content-folder"])->efToString("/");
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

    // public function unfold(): string
    // {
        // return UIKit::webHead()->favicons(
        //     "/assets/favicons/favicon.ico",
        //     "/assets/favicons/apple-touch-icon.png",
        //     "/assets/favicons/favicon-32x32.png",
        //     "/assets/favicons/favicon-16x16.png"
        // )->styles(
        //     ...$this->styles()
        // )->scripts(
        //     ...$this->scripts()
        // )->social(
        //     ...$this->social()
        // );

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
