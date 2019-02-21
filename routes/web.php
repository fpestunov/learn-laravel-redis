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
