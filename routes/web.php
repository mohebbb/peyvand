<?php

use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Short link redirection
|--------------------------------------------------------------------------
| Registered last as a single-segment catch-all. The short_code is
| constrained to exactly 6 characters from the generator's unambiguous
| alphabet, so it can never shadow /admin, /livewire/*, or asset routes.
*/
Route::get('/{short_code}', RedirectController::class)
    ->where('short_code', '[23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz]{6}')
    ->name('short-link.redirect');
