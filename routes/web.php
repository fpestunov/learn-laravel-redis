<?php

Route::get('/', function () {
    return Cache::remember('articles.all', 60 * 60, function () {
        // dd('test redis works...');
        return App\Article::all();
    });
});
