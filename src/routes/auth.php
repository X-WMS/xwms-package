<?php

use Illuminate\Support\Facades\Route;
use LaravelShared\Core\Controllers\LangController;

// ------------------------------------------------------
// --------- LANG
// ------------------------------------------------------

Route::middleware(['visitor', 'locale'])->group(function () {
    Route::get('/set/locale/{locale}', [LangController::class, 'setLocale'])->name('set.locale');
});