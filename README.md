# Laravel 5 Msg91 package

### About

MSG91 is a bulk SMS service provider offers transactional & promotional bulk SMS solutions internationally. This package provide the text SMS & SendOTP functionalities.

[Documentation - Text SMS](https://control.msg91.com/apidoc/textsms/send-sms.php)

[Documentation - sendOTP](https://control.msg91.com/apidoc/sendotp/send-otp.php)

### Registration

[Sign up for Msg91](https://msg91.com/signup) and get the auth key from your account. You can find the `auth key` from `Dashboard > API` after signing in.

### Installation

Installation via composer

Add `robincsamuel/laravel-msg91` to your composer requirements:

```php
"require": {
    "robincsamuel/laravel-msg91": "dev-master"
}
```

Now run `composer update`

Once the package is installed, open your `app/config/app.php` configuration file and locate the `providers` key.  Add the following line to the end:

```php
RobinCSamuel\LaravelMsg91\LaravelMsg91ServiceProvider::class
```

Next, locate the `aliases` key and add the following line:

```php
'LaravelMsg91' => RobinCSamuel\LaravelMsg91\Facades\LaravelMsg91::class,
```
Put the credentials & preferences in ENV with the keys `MSG91_KEY`, `MSG91_SENDER_ID`, `MSG91_ROUTE`, `MSG91_COUNTRY`. If you wan't to customize this, publish the default configuration which will create a config file  `config/msg91.php`.

```bash
$ php artisan vendor:publish
```

### Usage

1. Send an SMS to one or more numbers. See the package config file to set up API access.

    ```php

    $result = LaravelMsg91::message(919090909090, 'This is a test message');

    $result = LaravelMsg91::message(array('919090909090', '919090909091'), 'This is a test message to multiple recepients');

    ```
2. Send OTP

	```php

	$result = LaravelMsg91::sendOtp(919090909090, 1290);

	$result = LaravelMsg91::sendOtp(919090909090, 1290, "Your otp for phone verification is 1290");
	```

3. Resend OTP

	```php

	$result = LaravelMsg91::resendOtp(919090909090);

	$result = LaravelMsg91::resendOtp(919090909090, 'voice');
	```

3. Verify OTP

	```php

	$result = LaravelMsg91::verifyOtp(919090909090, 1290); // returns true or false

	$result = LaravelMsg91::verifyOtp(919090909090, 1290, ['raw' => true]); // returns what msg91 replies (includes error message & type)
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
