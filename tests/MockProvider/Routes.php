<?php

use Eightfold\DmsHelpers\Tests\MockProvider\Controllers\RootController;
use Eightfold\DmsHelpers\Tests\MockProvider\Controllers\AssetsController;
use Eightfold\DmsHelpers\Tests\MockProvider\Controllers\MediaController;

Route::get("/", RootController::class);

Route::prefix("assets")->group(function() {
    Route::get("{type}/{image}", AssetsController::class)->name("assets");
});

Route::prefix("media")->group(function() {
    Route::get("{any}", MediaController::class)->where("any", ".*");
});
