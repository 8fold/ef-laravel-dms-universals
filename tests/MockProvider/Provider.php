<?php

namespace Eightfold\DmsHelpers\Tests\MockProvider;

use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{
    public function register()
    {
        $this->loadRoutesFrom(__DIR__."/Routes.php");

        // $this->loadViewsFrom(__DIR__.'/Views', "le");
    }

    public function boot()
    {
    }
}
