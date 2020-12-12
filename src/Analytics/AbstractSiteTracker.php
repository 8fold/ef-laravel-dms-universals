<?php

namespace Eightfold\DmsHelpers\Analytics;

use Eightfold\Foldable\Fold;

use Carbon\Carbon;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

use Eightfold\ShoopShelf\Shoop;
use Eightfold\ShoopShelf\FluentTypes\ESStore;
// use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\Hash;



// use Eightfold\Shoop\{
//     Helpers\Type,
//     ESString
// };

// use Eightfold\ShoopExtras\{
//     Shoop,
//     ESStore
// };

/**
 * An abstract class for tracking session requests across pages.
 *
 * DOES NOT store any user-related or -generated data.
 */
abstract class AbstractSiteTracker extends Fold
{
    private $localRoot;
    private $id;

    private $crawlerDetector;

    private $carbon;
    private $timestamp;

    /**
     *
     * @param string $localRoot The fully qualified path to the root directory where the site tracking data should be stored.
     * @param string $id        A unique identifier which will be hashed, if you use the session ID, it's recommended you modify it in some way before passing it to the site tracker.
     */
    public function __construct(string $localRoot, string $id)
    {
        $this->localRoot = $localRoot;
        $this->id        = md5($id); // Hash the id
    }

    /**
     * Attempt to save session-related data.
     *
     * Crawlers (search engines and similar bots) are ignored.
     */
    public function savedRecord(): bool
    {
        return ($this->crawlerDetector()->isCrawler())
            ? false
            : $this->savedSessionRecord();
    }

    /**
     * @return string Local root for file storage.
     */
    public function localRoot(): string
    {
        return $this->localRoot;
    }

    /**
     * Id for tracking client across multiple URLs within same domain; used as a directory name.
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * The URL from which the request originated.
     */
    private function referrer(): string
    {
        $server = Shoop::this($_SERVER);
        if ($server->hasAt("HTTP_REFERER")->unfold()) {
            return $_SERVER["HTTP_REFERER"];
        }
        return "";
    }

    /**
     * The URL the user agent requested.
     */
    private function current(): string
    {
        $server = Shoop::this($_SERVER);
        if ($server->hasAt("REQUEST_URI")->unfold()) {
            // Don't include query strings
            return strtok($_SERVER["REQUEST_URI"], '?');
        }
        return "";
    }

    /**
     * Differentiate between a user agent most likely controlled by a human versus a bot or script.
     */
    private function crawlerDetector(): CrawlerDetect
    {
        if ($this->crawlerDetector === null) {
            $this->crawlerDetector = new CrawlerDetect;
        }
        return $this->crawlerDetector;
    }

    /**
     * cache property
     */
    private function carbon(): Carbon
    {
        if ($this->carbon === null) {
            $this->carbon = Carbon::now("UTC");
        }
        return $this->carbon;
    }

    /**
     * cache property
     */
    private function timestamp(): string
    {
        if ($this->timestamp === null) {
            $this->timestamp = $this->carbon()->format("YmdGis-v");
        }
        return $this->timestamp;
    }

    private function sessionPath(): string
    {
        return "/sessions/". $this->id() ."/". $this->fileName();
    }

    public function fileName(): string
    {
        return $this->timestamp() .".pageview";
    }

    private function baseContent(): array
    {
        return Shoop::this([
            "session" => $this->sessionPath(),
            "timestamp" => $this->timestamp()
        ])->unfold();
    }

    private function savedSessionRecord(): bool
    {
        $content = Shoop::this(
            $this->baseContent()
        )->append([
            "session" => $this->sessionPath(),
            "timestamp" => $this->timestamp(),
            "previous" => $this->referrer(),
            "current" => $this->current()
        ])->efToJson();

        $this->sessionStore()->saveContent($content);

        return $this->sessionStore()->isFile()->unfold() and
            $this->savedUrlRecord() and
            $this->savedDateRecord();
    }

    private function savedUrlRecord(): bool
    {
        return $this->savedStore(
            $this->urlStore()
        );
    }

    private function savedDateRecord(): bool
    {
        return $this->savedStore(
            $this->dateStore()
        );
    }

    private function savedStore(ESStore $store): bool
    {
        $store->saveContent(
            Shoop::this(
                $this->baseContent()
            )->efToJson()
        );

        return $store->isFile()->unfold();
    }

    private function sessionStore(): ESStore
    {
        return Shoop::store(
            $this->localRoot()
        )->append([
            $this->sessionPath()
        ]);
    }

    private function urlStore(): ESStore
    {
        $pathParts = Shoop::this(["urls"])->append(
            Shoop::this(
                $this->current()
            )->divide("/")->unfold()
        )->append([$this->fileName()])
        ->drop(fn($p) => empty($p))
        ->unfold();

        return Shoop::store(
            $this->localRoot()
        )->append($pathParts);
    }

    private function dateStore(): ESStore
    {
        $pathParts = Shoop::this(["dates"])->append(
            Shoop::this(
                $this->carbon()->format("/Y/m/d/Gis-v")
            )->divide("/")->unfold()
        )->drop(fn($p) => empty($p))
        ->asString("/")
        ->append(".pageview")
        ->divide("/")
        ->unfold();

        return Shoop::store(
            $this->localRoot()
        )->append($pathParts);
    }
}
