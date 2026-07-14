<?php

use App\Support\RoleRedirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        $redirect = RoleRedirect::toDashboard(Auth::user());
        if ($redirect) {
            return $redirect;
        }
    }

    return view('welcome');
})->name('home');

Route::view('/about', 'about')->name('about');
