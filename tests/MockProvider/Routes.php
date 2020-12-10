<?php

use Eightfold\DmsHelpers\Tests\MockProvider\Controllers\AssetsController;
use Eightfold\DmsHelpers\Tests\MockProvider\Controllers\MediaController;

Route::prefix("assets")->group(function() {
    Route::get("{type}/{image}", AssetsController::class)->name("asssets");
});

Route::prefix("media")->group(function() {
    Route::get("{any}", MediaController::class)->where("any", ".*");
});
