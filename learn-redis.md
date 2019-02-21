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
