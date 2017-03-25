# Laravel 5 Msg91 package

### About

MSG91 is a bulk SMS service provider offers transactional & promotional bulk SMS solutions internationally. This package provide the basic function, messsaging API.

[Documentation](https://control.msg91.com/apidoc/textsms/send-sms.php)

### Installation

Installation via composer...

Add `robincsamuel/laravel-msg91` to your composer requirements:

```php
"require": {
    "robincsamuel/laravel-msg91": "~1"
}
```

Now run `composer update`

Once the package is installed, open your `app/config/app.php` configuration file and locate the `providers` key.  Add the following line to the end:

```php
RobinCSamuel\LaravelMsg91\LaravelMsg91ServiceProvider::class
```

Next, locate the `aliases` key and add the following line:

```php
LaravelMsg91' => RobinCSamuel\LaravelMsg91\Facades\LaravelMsg91::class,
```
Put the credentials in ENV, with the keys `MSG91_KEY`, `MSG91_SENDER_ID`, `MSG91_ROUTE`, 'MSG91_COUNTRY'. If you wan't to customize this, publish the default configuration which will create a config file  `config/msg91.php`.

```bash
$ php artisan vendor:publish
```

### Usage

1. Send an SMS to one or more numbers. See the package config file to set up API access.

    ```php

    $result = LaravelMsg91::message(919090909090, 'This is a test message');

    ```

    Or

    ```php

    $result = LaravelMsg91::message(array('919090909090', '919090909091'), 'This is a test message to multiple recepients');

    ```

### License

The MIT License (MIT)

Copyright (c) 2017 Robin C Samuel

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
