<?php

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
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

Route::get('/hashes', function () {
    $user3stat = [
        'favorites' => 22,
        'watchLaters' => 33,
        'completions' => 11,
    ];

    Redis::hmset('user.3.stats', $user3stat);

    return Redis::hgetall('user.3.stats');

    // return Redis::hgetall('user.1.stats')['favorites'];
    // return Redis::hgetall('user.1.stats');
});

Route::get('/users/{id}/stats', function ($id) {
    // var_dump("user.{$id}.stats");
    return Redis::hgetall("user.{$id}.stats");
});

Route::get('favorite-video', function () {
    $id = 3; // Auth()->id();
    
    Redis::hincrby("user.{$id}.stats", 'favorites', 1);
    return redirect("/users/{$id}/stats");
});

Route::get('lara-cache', function () {

    // var_dump(env('CACHE_DRIVER', 'file'));
    Cache::put('foo2', 'bars', 12);

    return Cache::get('foo2');
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
