<?php

interface Articles
{
    public function all();
}

class CacheableArticles implements Articles
{
    protected $articles;

    public function __construct($articles)
    {
        $this->articles = $articles;
    }

    public function all()
    {
        return Cache::remember('articles.all', 60 * 60, function () {
            // return '555';
            return $this->articles->all();
        });
    }
}

class EloquentArticles implements Articles
{
    public function all()
    {
        return App\Article::all();
    }
}

App::bind('Articles', function () {
    return new CacheableArticles(new EloquentArticles);
});

Route::get('/', function (Articles $articles) {
    // dd(App::make('Articles'));
    // dd(resolve('Articles'));
    return $articles->all();
});
