<?php
declare(strict_types=1);

namespace Eightfold\DmsHelpers\Tests\MockProvider\ContentHelpers;

use Eightfold\DmsHelpers\ContentHelpers\AbstractMeta;

use Eightfold\ShoopShelf\Shoop;
use Eightfold\Markup\UIKit;

use Eightfold\DmsHelpers\Tests\MockProvider\ContentHelpers\Title;

class Meta extends AbstractMeta
{
    private $uriString; // path for user (web-client) request; where the user is.

    private $type = "website";

    static public function localRoot(): string
    {
        return Shoop::this(__DIR__)->divide("/")->dropLast(2)
            ->append(["content-folder"])->efToString("/");
    }

    public function type(string $type = "website"): string
    {
        $this->type = $type;
        return $this;
    }

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
            Title::fold(static::localRoot(), $this->uriString())->unfold(),
            url()->current(),
            $this->description(),
            $this->poster(),
            $this->type
        ];
    }

    public function description(): string
    {
        $store = Shoop::store(static::localRoot())
            ->append($this->uriParts())
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
            ->append($this->uriParts());
        $posters = Shoop::this($this->uriParts())->each(function($p) use (&$store) {
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
