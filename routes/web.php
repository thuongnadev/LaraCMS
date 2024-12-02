<?php

use Illuminate\Support\Facades\Route;

Route::get('sitemap.xml', function () {
    return response()->file(public_path('sitemap.xml'));
});
