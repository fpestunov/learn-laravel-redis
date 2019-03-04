# Learn Redis Through Examples

You've probably known for a while now that Redis is a thing. But, like so many others, maybe you've had trouble understanding exactly when you would reach for a key-value database like this. What's wrong with just using MySQL for everything, right? Well, as you'll find, there are a number of situations when Redis emerges as the best possible tool for the job. In this series, you'll work through a number of examples to get up and running with Laravel and Redis as quickly as possible.

### 1. Installation and a Visitor Counter
In this opening episode, we'll get Redis installed on your machine, before moving on to basic Redis usage in Laravel. Let's start with the most old-school of examples: a site visitor counter. As silly as it may be, should you need such a counter, Redis is a perfect choice.

Установка: Homestead, Windows 10, Linux, Mac
Работает как сервис=служба.

Устанавливаем Redis для PHP\Laravel
https://laravel.com/docs/5.7/redis
composer require predis/predis

Что это за ошибка???
predis/predis suggests installing ext-phpiredis (Allows faster serialization and deserialization of the Redis protocol)

НАчинаем программировать...
web.php

Как в Сублайм импортировать библиотеки???


https://redis.io/commands (как сделать абривиатуры? - плиточки)
https://redis.io/commands/incr
делаем команды в redis-cli

Добавляем счетчик посетителей.

## 2. Counters and Namespacing
Okay okay, so a website visitor counter is a bit old-fashioned. But, if you think about it, we increment counters all over the place: your number of followers, the times a video has been downloaded, the number of articles you've favorited, etc. Let's review one example in this episode, while also taking time to discuss key name-spacing.

Шаблоны. Шаблон для разных видео и как считать скачивания???
videos.$id.download
twitter.$name.watch
...что хочешь, то и считаешь!!!

CLI
просмотр:
KEYS videos.*

## 3. Trending Articles with Sorted Sets
You're going to love sorted sets. Think of them as unique arrays that automatically sort their items, according to a particular score that you define. You might sort the top scoring basketball teams, a forum leaderboard, or even the most popular video tutorials at Laracasts! When it comes to Redis, even two lines of code can accomplish so much.

В Редисе разные типы данных. В ПХП мы работаем с массивами. А Редисе это списки - "L"(ist). Все команды начинаются с L.
https://redis.io/commands#list

Или Хэш - ассоциативные массивы.
https://redis.io/commands#hash

Или Sets - тоже что List, только содержит уникальные значения.
1,2,3,4,5,1,2,4 => 1,2,3,4,5
https://redis.io/commands#set

Или Sorted Sets - отсортирован по Scores (как?)
https://redis.io/commands#sorted_set

Перейдем к практике, очистим базу данных:
flushall

Добавим информацию о нескольких статьях:
zadd trending_articles 1 'learn-php'
zadd trending_articles 1 'learn-html'
zadd trending_articles 1 'learn-css'

Какое общее количество?
zcard trending_articles

Как получить помощь по команде?
help zcard

Вывести список:
zrange trending_articles 0 -1

Вывести список WITHSCORES:
zrange trending_articles 0 -1 WITHSCORES

Вывести список WITHSCORES в обратном порядке - REV(erse):
zrevrange trending_articles 0 -1 WITHSCORES

Увеличить очки у элемента:
zincrby trending_articles 1 learn-html

Перейдем к программированию:
- создадим модель и миграцию, добавим поля и отправим в БД
php artisan make:model Article -mr

- сосдадим Фэйкер для заполнения данными...

- наполним базу данных статьями:
php artisan tinker
factory('App\Article', 10)->create()

- проверим результат:
App\Article::count()

Work!

Теперь напишем роут, вывод статьи:
Route::get('articles/{article}', function (App\Article $article) {
    return $article;
});

Статьи по номеру отдает. Теперь нам надо сделать, чтобы считались просмотры этих статей... и чтобы на отдельной странице отображались 3 популярные статьи.

Код написан, очищаем память Редиса и начинаем тестировать.
flushall

Отлично, работает и возвращает массив id популярных статей.

А как сделать вывод самих статей? Доработаем наши роуты... Используем App\Article::hydrate(). Готово. Работает.

Вот еще - статей много, статистика накапливается, а нам надо всего - 3, 5, 10 лучших статей. ЗАчем нам хранить лишнее??

Поможет: rem ('remove from range')
Redis::zremrangebyrank('trending_articles', 0, -4);

Готово, работает. Хотя для блогов это не обязательно, т.к. не съедает много ресурсов...

## 4. Hashes and Caching
https://redis.io/commands#hash

Next up, let's review Redis' hash data type. Think of these as a Redis equivalent to PHP's associative array. When you need to associate a number of key-value pairs with a single key, this is the type you should reach for. Near the conclusion of this lesson, we'll also touch upon Laravel's Cache component, and how that fits in with our Redis review.

Пример статистики пользователя:
- пройдено курсов;
- любимые темы;
- и т.д.
$user1stat = [
    'favorites' => 50,
    'watchLaters' => 80,
    'completions' => 23,
];

Редис хороший вариант!
H = "hashes"
m = "multible"
hset - только 'favorites' => 50
hmset - весь массив - $user1$stat

Запускаем `redis-cli`:
help hmset
hmset user.1.stats favorites 50 watchLaters 90 completions 25
hget user.1.stats favorites
// 50
hgetall user.1.stats

Выведем значения на странице:
    return Redis::hgetall('user.1.stats')['favorites'];
    return Redis::hgetall('user.1.stats');

Добавим статистику по второму и третьему пользователю.
И сделаем страницу статистики. Отлично!

Так, а как нам увеличивать favorite-video? Создадим еще один роут 'favorite-video'.

### Есть еще тема КЭШа - Laravel Cache
https://laravel.com/docs/5.7/cache

config/cache.php
// Supported: "apc", "array", "database", "file", "memcached", "redis"
// 'default' => env('CACHE_DRIVER', 'file'),

правим это значение в файле `.env`

префиксы ключей:
laravel_cache

Почему то не сработало?! не помещает ключи в Редис... :(
а! нет в Redis Desctop Manager значения есть...



## 5. Caching With Redis

Caching, of course, is an incredible use-case for Redis. Whether you're caching database queries, API calls, or even HTML fragments, you'll get a lot of use out of the techniques within this episode. We'll begin by building up a custom remember function, before switching over to Laravel's Cache component.

Все очищаем в роутах. И рассмотрим пример запросов наших статей. Каждый визит - это запрос к БД. А если блог обновляется 1 раз в неделю? Хороший пример для использования кэширования. Можно кэшировать запросы, фрагменты страниц и т.д.

из - json_decode()
в - json_encode()
в - $articles->toJson()

Тестируем - первое обращение записывает в кэш, а при следующих берет оттуда и в консоли Редиса мы видим данные:
```
get articles.all
```

Эта команда удаляет ключ:
```
del articles.all
```

Переделаем работу использую функцию `remember()`. Работает. Посмотрим, в Ларавеле все реализовано таким же образом! Переделаем на Ларавел лад.

## 6. How to Structure Your Caching Layer

Before we move on to learning about PubSub in Redis, let's take a short break to review how we might organize our PHP to best take advantage of caching. In the previous episode, we simply wrapped our database query in a call to Cache::remember(). But, what if we want to dry up our code a bit, or even turn the caching on and off (through the use of decorators), like the flick of a switch? In this episode, I'll demonstrate a few options that you might consider.

Что если мы захотим использовать этот код в других местах?

### i. вынесем функциональность в отдельный класс

### ii. используя Декоратор, расширим возможности

Одинаковый результат, но вторая строка более читаемая и предпочтительна:
- dd(App::make('Articles'));
- dd(resolve('Articles'));
