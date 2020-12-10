<?php

namespace Eightfold\DmsHelpers\ContentHelpers;

use Eightfold\Foldable\Fold;

use Eightfold\ShoopShelf\Shoop;

// use LiberatedElephant\Site\Store;

abstract class AbstractTitle extends Fold
{
    abstract static public function localRoot(): string;

    private $uriString; // path for user (web-client) request; where the user is.

    public function __construct(string $uriString)
    {
        $this->uriString = $uriString;
    }

    private function uriParts(): array
    {
        return Shoop::this($this->uriString)->divide("/")
            ->drop(fn($p) => empty($p))->efToArray();
    }

    public function unfold(): string
    {
        // if (Shoop::this($this->uriParts())->efIsEmpty()) {
        //     return "Root title";
        // }

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
    // private $store;

    // private $checkHeadingFirst = false;

    // private $type = "page";

    // public function __construct(Store $store, bool $checkHeadingFirst = false)
    // {
    //     $this->store = $store;
    //     $this->checkHeadingFirst = $checkHeadingFirst;
    // }

    // public function store(): Store
    // {
    //     return $this->store;
    // }

    // public function headingFirst(): bool
    // {
    //     return $this->checkHeadingFirst;
    // }

    // public function bookend(): PageTitle
    // {
    //     $this->type = "book-end";
    //     return $this;
    // }

    // public function titles(): array
    // {
    //     $store  = $this->store();
    //     $titles = $store->parts()->reversed()->each(function($v, $m, &$build, &$break) use (&$store) {
    //         $s = $store->append(["content.md"]);
    //         if ($s->isFile()) {
    //             $meta = $s->markdown()->meta();
    //             if ($this->headingFirst() and $meta->hasAt("heading")->unfold()) {
    //                 $build[] = $meta->at("heading")->unfold();

    //             } elseif ($meta->hasAt("title")->unfold()) {
    //                 $build[] = $meta->at("title")->unfold();

    //             } else {
    //                 $build[] = "";

    //             }

    //             if ($meta->hasAt("root")->unfold() and $meta->at("root")->unfold()) {
    //                 $break = true;

    //             }
    //         }

    //         $s = $store->parts()->dropLast()->efToString("/");
    //         $store = Shoop::store($s);
    //     });

    //     if ($titles->efIsEmpty()) {
    //         return [];
    //     }
    //     return $titles->unfold();
    // }

    // public function unfold(): string
    // {
    //     $titles = $this->titles();
    //     return Shoop::this($titles)->efToString(" | ");
    // }

    // // string $type = "", bool $checkHeadingFirst = true, array $parts = []
    // //
    // /**
    //  * Heading member from YAML front matter, falls back to title member,
    //  * if heading not set.
    //  */
    // public const HEADING = "heading";

    // /**
    //  * Recursively uses title member from YAML front matter to build a fully-
    //  * qualified title string with separator. ex. Leaf | Branch | Trunk | Root
    //  */
    // public const PAGE = "page";

    // /**
    //  * Title member from YAML front matter.
    //  */
    // public const TITLE = "title";

    // /**
    //  * Uses the title member from YAML front matter to build a two-part title,
    //  * which includes the title for the current URL plus the title of the root
    //  * page with a separater. ex. Leaf | Root
    //  */
    // public const BOOKEND = "book-end";

    // public function pageTitle(string $type = "", bool $checkHeadingFirst = true, array $parts = []): string
    // {
    //     if (Shoop::this($type)->length()->isGreaterThan(0)->unfold()) {
    //         $type = static::PAGE;
    //         $checkHeadingFirst = false;
    //     }

    //     if (Shoop::this($parts)->efIsEmpty()) {
    //         $parts = static::uri(true);
    //     }

    //     $titles = [];
    //     if ($checkHeadingFirst and Shoop::this(static::HEADING)->is($type)->unfold()) {
    //         $titles[] = $this->titles($checkHeadingFirst, $parts)->first()->unfold();

    //     } elseif (Shoop::this(static::TITLE)->is($type)->unfold()) {
    //         $titles[] = $this->titles(false, $parts)->first()->unfold();

    //     } elseif (Shoop::this(static::BOOKEND)->is($type)->unfold()) {
    //         if (Shoop::this($this->uri(true))->efIsEmpty()) {
    //             $titles[] = $this->titles($checkHeadingFirst, $parts)->first()->unfold();
    //         }
    //     }
    //     return Shoop::this($titles)->drop(fn($v) => empty($v))->efToString(" | ");
    // }

    // public function title($type = "", $checkHeadingFirst = true, $parts = []): string
    // {
    //     if (strlen($type) === 0) {
    //         $type = static::PAGE;
    //         $checkHeadingFirst = false;
    //     }

    //     if (Shoop::this($parts)->efIsEmpty()) {
    //         $parts = static::uri(true);
    //     }

    //     $titles = [];
    //     if ($checkHeadingFirst and
    //         Shoop::this(static::HEADING)->is($type)->unfold()
    //     ) {
    //         $titles = $titles->plus(
    //             $this->titles($checkHeadingFirst, $parts)->first()
    //         );

    //     } elseif (Shoop::this(static::TITLE)->is($type)->unfold()) {
    //         $titles = $titles->plus(
    //             $this->titles(false, $parts)->first()
    //         );

    //     } elseif (Shoop::this(static::BOOKEND)->is($type)->unfold()) {
    //         if (Shoop::this($this->uri(true))->efIsEmpty()) {
    //             $t = $this->titles($checkHeadingFirst, $parts);
    //             if (Shoop::this($t)->length()->isEmpty()->reversed()->unfold()) {
    //                 $titles[] = Shoop::this($t)->first();
    //             }

    //         } else {
    //             $t = $this->titles($checkHeadingFirst, $parts)->divide(-1);
    //             $start = $t->first()->first();
    //             $root = $t->last()->first();
    //             if ($this->uri(true)->isUnfolded("events")) {
    //                 $eventTitles = $this->eventsTitles();
    //                 $start = $start->start($eventTitles->month ." ". $eventTitles->year);
    //                 $root = $this->contentStore(true)->markdown()->meta()->title();
    //             }

    //             $titles = $titles->plus($start, $root);
    //         }

    //     } elseif (Shoop::string(static::PAGE)->isUnfolded($type)) {
    //         $t = $this->titles(false, $parts)->divide(-1);
    //         $start = $t->first();
    //         $root = $t->last();
    //         if ($this->uri(true)->isUnfolded("events")) {
    //             die("here");
    //             $eventTitles = $this->eventsTitles(
    //                 $type = "",
    //                 $checkHeadingFirst = true,
    //                 $parts = []
    //             );
    //             $start = $start->start($eventTitles->month, $eventTitles->year);
    //         }
    //         $titles = $titles->plus(...$start)->plus(...$root);

    //     }
    //     return Shoop::this($titles)->drop(fn($v) => empty($v))->efToString(" | ");
    // }
}
