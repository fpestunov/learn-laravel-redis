<?php

class Articles
{
    public function all()
    {
        return Cache::remember('articles.all', 60 * 60, function () {
            return App\Article::all();
        });
        
    }
}

Route::get('/', function (Articles $articles) {
    return $articles->all();
});
