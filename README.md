**This is a draft. Don't use in production**

# kiwi-suite/pushing-kiwi-client

## Installation

The suggested installation method is via [composer](https://getcomposer.org/):

```sh
php composer.phar require kiwi-suite/pushing-kiwi-client
```

## Usage

```php
$iosMessage = new IosMessage([
    'title' => "string",
    'body' => "string",
    'launchImage' => "string",
    'badge' => 1,
    'sound' => "string",
    'payload' => ["key" => "value"],
    'priority' => 5,
    'deviceIds' => ["deviceToken1", "deviceToken2"],
]);

$androidMessage = new AndroidMessage([
    'payload' => ["key" => "value"],
    'deviceIds' => ["deviceToken1", "deviceToken2"],
]);

$notification = new Notification("my_secret_token", [$iosMessage, $androidMessage]);
$psr7Request = $notification->createHttpRequest();
$yourHttpClient->send($psr7Request);
```

This library does not ship with functionality to send HTTP requests over the wire. You need to get a library to do this for you. Any PSR-7 compliant library will work, like [Guzzle v6+][guzzle6].

    $ composer require 'guzzlehttp/guzzle:^6.0.0'

[guzzle6]: http://guzzle.readthedocs.org/en/latest/
