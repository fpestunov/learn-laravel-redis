<?php

use Illuminate\Support\Facades\Redis;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $visits = Redis::incr('visits');

    // return $visits;

    return view('welcome')->withVisits($visits);
});

Route::get('video/{id}', function ($id) {
    $downloads = Redis::get("videos.{$id}.downloads");
    // var_dump($downloads);

    return view('second')->withDownloads($downloads);
});

Route::get('video/{id}/download', function ($id) {
    Redis::incr("videos.{$id}.downloads");
    
    return back();
});

Route::get('articles/trending', function () {
    $trending = Redis::zrevrange('trending_articles', 0, 2);

    $trending = App\Article::hydrate(
        array_map('json_decode', $trending)
    );
    // dd($trending); // выводит коллекцию - массив объектов Article
    return $trending; // выводит массив
});

Route::get('articles/{article}', function (App\Article $article) {
    Redis::zincrby('trending_articles', 1, $article);
    // Redis::zincrby('trending_articles', 1, $article->id);

    // храним список лучших 3 статей
    Redis::zremrangebyrank('trending_articles', 0, -4);
    
    return $article;
});
